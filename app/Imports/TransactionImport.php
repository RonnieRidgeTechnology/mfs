<?php

namespace App\Imports;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendAnnualFeeEmailJob;

class TransactionImport implements ToCollection
{
    protected $flaggedRecords = [];
    protected $flagStatusZeroRecords = [];
    protected $newTransactions = [];
    protected $guestUsers = [];
    protected $noIdFlaggedTransactions = [];
    protected $processedCount = 0;
    protected $errorCount = 0;

    public function collection(Collection $rows)
    {
        Log::info('🚀 Starting import with ' . $rows->count() . ' rows');
        Log::info('📊 Row data sample: ' . json_encode($rows->take(3)->toArray()));

        foreach ($rows as $index => $row) {
            Log::info('🔍 Processing row ' . ($index + 1) . ' - Raw data: ' . json_encode($row->toArray()));

            try {
                // Skip header row and empty rows
                if ($index === 0 || empty(array_filter($row->toArray()))) {
                    Log::info('⏭️ Skipping row ' . ($index + 1) . ' (header or empty)');
                    continue;
                }

                // Log the row data for debugging
                Log::info('Processing row ' . ($index + 1) . ': ' . json_encode($row->toArray()));

                // STEP 1: Detect CSV format and extract data accordingly
                $dateString = '';
                $account = '';
                $amount = '';
                $subcategory = '';
                $memo = '';

                // Check if column 1 contains comma-separated data (March/April format)
                if (strpos($row[1] ?? '', ',') !== false) {
                    // March/April format: comma-separated data in column 1
                    $mainData = $row[1] ?? '';
                    $dataParts = explode(',', $mainData);

                    if (count($dataParts) >= 6) {
                        $dateString = trim($dataParts[1] ?? ''); // "03/03/2023"
                        $account = trim($dataParts[2] ?? ''); // "20-43-04 70954624"
                        $amount = trim($dataParts[3] ?? ''); // "50.00"
                        $subcategory = trim($dataParts[4] ?? ''); // "Standing Order"
                        $memo = trim($dataParts[5] ?? ''); // "M YOUNIS"

                        // Additional info from column 2
                        $additionalInfo = $row[2] ?? '';
                        $memo .= "\t" . $additionalInfo;
                    }

                    Log::info('March/April format detected - Date: ' . $dateString . ', Account: ' . $account . ', Amount: ' . $amount . ', Subcategory: ' . $subcategory . ', Memo: ' . $memo);
                } else {
                    // February format: separate columns
                    $dateString = $row[1] ?? '';
                    $account = $row[2] ?? '';
                    $amount = $row[3] ?? '';
                    $subcategory = $row[4] ?? '';
                    $memo = $row[5] ?? '';

                    Log::info('February format detected - Date: ' . $dateString . ', Account: ' . $account . ', Amount: ' . $amount . ', Subcategory: ' . $subcategory . ', Memo: ' . $memo);
                }

                // STEP 2: Parse date
                $date = null;
                try {
                    // Handle date format like "06/04/2023"
                    $date = Carbon::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
                    Log::info('Parsed date: ' . $date);
                } catch (\Exception $e) {
                    Log::error('Invalid date in row ' . ($index + 1) . ': ' . $dateString);
                    $this->errorCount++;
                    continue;
                }

                // STEP 3: Validate amount
                if (!is_numeric($amount)) {
                    Log::error('Invalid amount in row ' . ($index + 1) . ': ' . $amount);
                    $this->errorCount++;
                    continue;
                }

                // STEP 4: Map subcategory to status
                $status = $this->mapStatus($subcategory);

                // STEP 5: Extract name and unique ID from memo
                // Store the COMPLETE memo as the name (as requested by user)
                // Memo format examples:
                // "FATIMA K B            	K7A12W7R10N13 STO	"
                // "Fatima S              \tS43 BGC\t"
                // "M YOUNIS\tAdditional Info"

                // Use the complete memo as the name, just clean up excessive whitespace
                $name = trim($memo);
                $name = preg_replace('/\s+/', ' ', $name); // Replace multiple spaces with single space
                $name = str_replace("\t", " ", $name); // Replace tabs with spaces
                $name = trim($name); // Final trim

                Log::info('Complete memo as name - Original memo: "' . $memo . '"');
                Log::info('Complete memo as name - Final name: "' . $name . '" (Length: ' . strlen($name) . ')');

                // Still extract unique ID for user matching (but name will be the complete memo)
                $memoParts = explode("\t", $memo);
                $uniqueId = null;

                $uniqueId = null;

                // Extract unique ID from all parts of the memo
                $fullMemo = $memo;
                preg_match('/\b([A-Z]\d+)\b/', $fullMemo, $matches);
                $uniqueId = $matches[1] ?? null;

                Log::info('Name extraction - Raw memo: "' . $memo . '"');
                Log::info('Name extraction - Memo parts: ' . json_encode($memoParts));
                Log::info('Name extraction - Complete memo used as name: "' . $name . '" (Length: ' . strlen($name) . ')');
                Log::info('Extracted unique ID from memo: ' . ($uniqueId ?? 'null'));

                if (empty($name)) {
                    Log::error('No name found in memo: ' . $memo);
                    $this->errorCount++;
                    continue;
                }

                Log::info('Extracted data - Name: "' . $name . '" (Length: ' . strlen($name) . '), Unique ID: ' . ($uniqueId ?? 'null') . ', Amount: ' . $amount . ', Status: ' . $status . ', Account: ' . $account . ', Memo: "' . $memo . '"');

                // STEP 7: Check if original unique ID was found in the transaction data
                $originalUniqueIdFound = !empty($uniqueId);
                $user = null;
                $userAlreadyExists = false;

                if ($originalUniqueIdFound) {
                    // Original logic: find or create user when unique ID exists
                    Log::info('Original unique ID found: ' . $uniqueId);

                    // STEP 8: Find user by unique_id (including guest users with matching original_unique_id)
                    $user = User::where('unique_id', $uniqueId)
                        ->where('type', 'member')
                        ->first();

                    // If not found, also check guest users with matching original_unique_id
                    if (!$user) {
                        $user = User::where('is_guest', 1)
                            ->where('original_unique_id', $uniqueId)
                            ->first();

                        if ($user) {
                            Log::info('Found existing guest user with matching original ID: ' . $user->name . ' (ID: ' . $user->id . ', Unique ID: ' . $user->unique_id . ', Original ID: ' . $user->original_unique_id . ')');
                        }
                    }

                    // Also check for promoted members who have the original_unique_id as their unique_id
                    if (!$user) {
                        $user = User::where('unique_id', $uniqueId)
                            ->where('is_guest', 0)
                            ->where('type', 'member')
                            ->first();

                        if ($user) {
                            Log::info('Found existing member with matching unique ID: ' . $user->name . ' (ID: ' . $user->id . ', Unique ID: ' . $user->unique_id . ')');
                        }
                    }

                    if (!$user) {
                        // Create new guest user if not found
                        $guestUser = $this->createGuestUser($uniqueId, $name);
                        if (!$guestUser) {
                            Log::error('Failed to create guest user for unique ID: ' . $uniqueId);
                            $this->errorCount++;
                            continue;
                        }
                        $user = $guestUser;
                        $userAlreadyExists = false; // New user created
                        Log::info('Created new guest user: ' . $user->name . ' (ID: ' . $user->id . ', Unique ID: ' . $user->unique_id . ')');
                    } else {
                        $userAlreadyExists = true; // User already existed
                        Log::info('Using existing user: ' . $user->name . ' (ID: ' . $user->id . ', Unique ID: ' . $user->unique_id . ')');
                    }
                } else {
                    // New logic: no unique ID found in original data, don't create guest user
                    Log::info('No unique ID found in original transaction data for: ' . $name . ' - will create transaction with null user_id and no_id_flagged = 0');
                    $user = null;
                    $userAlreadyExists = false;
                }

                if ($user) {
                    Log::info('Found user: ' . $user->name . ' (ID: ' . $user->id . ', Type: ' . $user->type . ')');

                    // STEP 8: Check if transaction exists for this user on this date
                    $exists = Transaction::where('user_id', $user->id)
                        ->where('date', $date)
                        ->exists();

                    Log::info('Transaction exists for this date: ' . ($exists ? 'Yes' : 'No'));

                    // STEP 9: Determine flag_status
                    // flag_status = 0 (flagged) if user already exists AND transaction exists for same date
                    // flag_status = 1 (normal) for all other cases
                    $flagStatus = ($userAlreadyExists && $exists) ? 0 : 1;

                    Log::info('Flag status determined: ' . $flagStatus . ' (User already existed: ' . ($userAlreadyExists ? 'Yes' : 'No') . ', Transaction exists: ' . ($exists ? 'Yes' : 'No') . ')');

                    // STEP 10: Create the transaction with user
                    $transaction = Transaction::create([
                        'date' => $date,
                        'account' => $account,
                        'amount' => $amount,
                        'status' => $status,
                        'user_id' => $user->id,
                        'flag_status' => $flagStatus,
                        'no_id_flaged' => 1, // Has user ID
                    ]);

                    Log::info('Created transaction with ID: ' . $transaction->id . ', flag_status: ' . $transaction->flag_status . ', user_id: ' . $user->id);

                    // Dispatch the annual fee email check (queued) - use job instead of controller method
                    try {
                        SendAnnualFeeEmailJob::dispatch($user->id, date('Y', strtotime($date)));
                        Log::info('Dispatched SendAnnualFeeEmailJob for user_id: ' . $user->id . ', year: ' . date('Y', strtotime($date)));
                    } catch (\Throwable $e) {
                        Log::error('Failed to dispatch SendAnnualFeeEmailJob: ' . $e->getMessage());
                    }
                } else {
                    // STEP 10: Create transaction without user (no_id_flagged)
                    $exists = false; // No user, so no existing transaction check needed
                    $flagStatus = 1; // Normal status for no-ID transactions

                    $transaction = Transaction::create([
                        'date' => $date,
                        'account' => $account,
                        'amount' => $amount,
                        'status' => $status,
                        'user_id' => null, // No user ID
                        'flag_status' => $flagStatus,
                        'no_id_flaged' => 0, // No user ID found
                        'name' => $name, // Store the name from import data
                    ]);

                    Log::info('Created no-ID transaction with ID: ' . $transaction->id . ', no_id_flaged: 0');
                }

                // STEP 11: Track records for response
                if ($user) {
                    // Transaction with user
                    $transactionData = [
                        'user_id' => $user->unique_id,
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                        'user_phone' => $user->phone,
                        'status' => $status,
                        'date' => $date,
                        'account' => $account,
                        'amount' => $amount,
                    ];

                    if ($exists) {
                        // This is a duplicate (flagged) - only add to flag_status_zero records
                        $this->flagStatusZeroRecords[] = $transactionData;
                        Log::info('🚩 Added to flag_status_zero records (duplicate)');
                    } else {
                        // This is a new transaction
                        $this->newTransactions[] = [
                            'user' => [
                                'unique_id' => $user->unique_id,
                                'name' => $user->name,
                                'email' => $user->email,
                                'phone' => $user->phone,
                            ],
                            'status' => $status,
                            'date' => $date,
                            'account' => $account,
                            'amount' => $amount,
                            'flag_status' => 1,
                        ];
                        Log::info('✅ Added to new transactions');
                    }
                } else {
                    // Transaction without user (no_id_flagged)
                    $noIdTransactionData = [
                        'transaction_id' => $transaction->id,
                        'name' => $name,
                        'status' => $status,
                        'date' => $date,
                        'account' => $account,
                        'amount' => $amount,
                        'no_id_flaged' => 0,
                    ];

                    $this->noIdFlaggedTransactions[] = $noIdTransactionData;
                    Log::info('🔍 Added to no_id_flagged transactions');
                }

                $this->processedCount++;

            } catch (\Exception $e) {
                Log::error('Error processing row ' . ($index + 1) . ': ' . $e->getMessage());
                $this->errorCount++;
            }
        }

        Log::info('🎯 Import completed. Processed: ' . $this->processedCount . ', Errors: ' . $this->errorCount);
        Log::info('🚩 Flagged records: ' . count($this->flaggedRecords));
        Log::info('🚩 Flag status zero records: ' . count($this->flagStatusZeroRecords));
        Log::info('✅ New transactions: ' . count($this->newTransactions));
        Log::info('👥 Guest users: ' . count($this->guestUsers));
        Log::info('📊 Final counts - Flagged: ' . count($this->flagStatusZeroRecords) . ', New: ' . count($this->newTransactions) . ', Guests: ' . count($this->guestUsers));
    }

    /**
     * Map the subcategory to a valid status
     */
    private function mapStatus($subcategory)
    {
        $subcategory = strtolower(trim($subcategory));
        Log::info('Mapping subcategory: ' . $subcategory);

        switch ($subcategory) {
            case 'counter credit':
            case 'standing order':
            case 'credit':
                return 'credit'; // Map to 'cash' for credit transactions
            case 'debit':
            case 'funds transfer':
                return 'debit';
            default:
                Log::info('Unknown subcategory, defaulting to cash: ' . $subcategory);
                return 'cash'; // Default to 'cash' for unknown types
        }
    }

    // Getter methods to access the results
    public function getFlaggedRecords()
    {
        return $this->flaggedRecords;
    }

    public function getFlagStatusZeroRecords()
    {
        return $this->flagStatusZeroRecords;
    }

    public function getNewTransactions()
    {
        return $this->newTransactions;
    }

    public function getGuestUsers()
    {
        return $this->guestUsers;
    }

    public function getNoIdFlaggedTransactions()
    {
        return $this->noIdFlaggedTransactions;
    }

    public function getProcessedCount()
    {
        return $this->processedCount;
    }

    public function getErrorCount()
    {
        return $this->errorCount;
    }

    /**
     * Create a guest user with the given unique ID and name
     */
    private function createGuestUser($uniqueId, $name)
    {
        try {
            // For guest users, always generate a unique ID starting with "G"
            $guestUniqueId = $this->generateGuestUniqueId();

            // Create guest user with generated "G" unique ID and provided name
            $guestUser = User::create([
                'unique_id' => $guestUniqueId,
                'original_unique_id' => $uniqueId, // Save the original unique ID
                'name' => $name,
                'email' => 'guest_' . $guestUniqueId . '@gmail.com',
                'password' => bcrypt('guest123'), // Temporary password
                'type' => 'member',
                'is_user' => 2,
                'is_guest' => 1,
                'status' => 1,
            ]);

            Log::info('Created guest user with ID: ' . $guestUser->id . ', Unique ID: ' . $guestUser->unique_id . ', Name: ' . $guestUser->name . ', Original ID: ' . $uniqueId);

            // Track guest user for response
            $this->guestUsers[] = [
                'id' => $guestUser->id,
                'unique_id' => $guestUser->unique_id,
                'name' => $guestUser->name,
                'email' => $guestUser->email,
                'original_unique_id' => $uniqueId,
            ];

            return $guestUser;

        } catch (\Exception $e) {
            Log::error('Error creating guest user: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate a unique guest ID starting with "G"
     */
    private function generateGuestUniqueId()
    {
        $prefix = 'GU';
        $counter = 1;

        do {
            $uniqueId = $prefix . $counter;
            $exists = User::where('unique_id', $uniqueId)->exists();
            $counter++;
        } while ($exists);

        return $uniqueId;
    }

    /**
     * Generate unique ID from name using User model method
     */
    private function generateUniqueIdFromName($name)
    {
        $user = new User();
        return $user->generateUniqueId($name);
    }
}
