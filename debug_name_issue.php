<?php
// Debug script to check name storage in database
// Run this in tinker: php artisan tinker

echo "=== DEBUGGING NAME STORAGE ISSUE ===\n";

// Get the latest No ID flagged transactions
$noIdTransactions = \App\Models\Transaction::where('no_id_flaged', 0)
    ->orderBy('created_at', 'desc')
    ->take(10)
    ->get(['id', 'name', 'date', 'amount', 'created_at']);

echo "Found " . $noIdTransactions->count() . " No ID flagged transactions:\n\n";

foreach($noIdTransactions as $transaction) {
    echo "ID: {$transaction->id}\n";
    echo "Name: '{$transaction->name}'\n";
    echo "Name Length: " . strlen($transaction->name) . " characters\n";
    echo "Date: {$transaction->date}\n";
    echo "Amount: {$transaction->amount}\n";
    echo "Created: {$transaction->created_at}\n";
    echo "Raw Name (with quotes): \"" . $transaction->name . "\"\n";
    echo "Name bytes: " . bin2hex($transaction->name) . "\n";
    echo "---\n";
}

// Check if there are any recent import logs
echo "\n=== RECENT IMPORT LOGS ===\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $lines = file($logFile);
    $recentLines = array_slice($lines, -50); // Last 50 lines
    
    foreach($recentLines as $line) {
        if (strpos($line, 'Name extraction') !== false || 
            strpos($line, 'Extracted data - Name:') !== false ||
            strpos($line, 'Raw name part:') !== false) {
            echo trim($line) . "\n";
        }
    }
} else {
    echo "Log file not found\n";
}
?>
