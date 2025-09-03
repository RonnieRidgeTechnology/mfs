<?php
// Test script to debug name extraction
// You can run this in tinker or create a test route

// Sample memo data from your Excel file
$testMemos = [
    "Fatima S              \tS43 BGC\t",
    "M YOUNIS\tAdditional Info",
    "John Smith\tA123 BGC",
    "Sarah Johnson",
    "Muhammad Ali Khan\tS456",
    "Dr. Ahmed Hassan\tD789 BGC\tExtra Info"
];

echo "=== TESTING NAME EXTRACTION ===\n\n";

foreach ($testMemos as $index => $memo) {
    echo "Test " . ($index + 1) . ":\n";
    echo "Original memo: \"$memo\"\n";
    
    // Simulate the extraction logic
    $memoParts = explode("\t", $memo);
    $rawName = $memoParts[0] ?? '';
    $name = trim($rawName);
    
    // Alternative extraction if name is too short
    if (empty($name) || strlen($name) < 2) {
        if (!empty($memo)) {
            $tempName = trim($memo);
            $tempName = preg_replace('/\s+[A-Z]\d+\s*$/', '', $tempName);
            $tempName = trim($tempName);
            if (strlen($tempName) > strlen($name)) {
                $name = $tempName;
            }
        }
        
        if (count($memoParts) > 1 && (empty($name) || strlen($name) < 2)) {
            for ($i = 0; $i < count($memoParts); $i++) {
                $part = trim($memoParts[$i]);
                if (!preg_match('/^[A-Z]\d+$/', $part) && 
                    !preg_match('/^\d{2}-\d{2}-\d{2}/', $part) && 
                    strlen($part) > 2) {
                    $name = $part;
                    break;
                }
            }
        }
    }
    
    // Extract unique ID
    preg_match('/\b([A-Z]\d+)\b/', $memo, $matches);
    $uniqueId = $matches[1] ?? null;
    
    echo "Memo parts: " . json_encode($memoParts) . "\n";
    echo "Raw name: \"$rawName\"\n";
    echo "Final name: \"$name\" (Length: " . strlen($name) . ")\n";
    echo "Unique ID: " . ($uniqueId ?? 'null') . "\n";
    echo "---\n\n";
}

// Test with actual database data
echo "=== CHECKING ACTUAL DATABASE DATA ===\n";
try {
    $recentTransactions = \App\Models\Transaction::where('no_id_flaged', 0)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get(['id', 'name', 'created_at']);
    
    foreach ($recentTransactions as $transaction) {
        echo "ID: {$transaction->id}\n";
        echo "Stored name: \"{$transaction->name}\"\n";
        echo "Name length: " . strlen($transaction->name) . " characters\n";
        echo "Created: {$transaction->created_at}\n";
        echo "---\n";
    }
} catch (Exception $e) {
    echo "Error accessing database: " . $e->getMessage() . "\n";
}
?>
