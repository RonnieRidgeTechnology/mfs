<?php
// Test script to verify complete memo storage
// Run this in tinker: php artisan tinker

echo "=== TESTING COMPLETE MEMO STORAGE ===\n\n";

// Test the new logic with your exact memo format
$testMemo = "FATIMA K B            	K7A12W7R10N13 STO	";

echo "Original memo: \"$testMemo\"\n";
echo "Memo length: " . strlen($testMemo) . " characters\n";
echo "Memo bytes: " . bin2hex($testMemo) . "\n\n";

// Simulate the new extraction logic
$name = trim($testMemo);
$name = preg_replace('/\s+/', ' ', $name); // Replace multiple spaces with single space
$name = str_replace("\t", " ", $name); // Replace tabs with spaces
$name = trim($name); // Final trim

echo "Processed name: \"$name\"\n";
echo "Processed name length: " . strlen($name) . " characters\n";
echo "Processed name bytes: " . bin2hex($name) . "\n\n";

// Extract unique ID
$memoParts = explode("\t", $testMemo);
$fullMemo = $testMemo;
preg_match('/\b([A-Z]\d+)\b/', $fullMemo, $matches);
$uniqueId = $matches[1] ?? null;

echo "Memo parts: " . json_encode($memoParts) . "\n";
echo "Extracted unique ID: " . ($uniqueId ?? 'null') . "\n\n";

// Test with database if available
echo "=== CHECKING RECENT DATABASE ENTRIES ===\n";
try {
    $recentTransactions = \App\Models\Transaction::where('no_id_flaged', 0)
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get(['id', 'name', 'created_at']);
    
    if ($recentTransactions->count() > 0) {
        foreach ($recentTransactions as $transaction) {
            echo "ID: {$transaction->id}\n";
            echo "Stored name: \"{$transaction->name}\"\n";
            echo "Name length: " . strlen($transaction->name) . " characters\n";
            echo "Created: {$transaction->created_at}\n";
            echo "Name contains tabs: " . (strpos($transaction->name, "\t") !== false ? 'Yes' : 'No') . "\n";
            echo "---\n";
        }
    } else {
        echo "No No-ID flagged transactions found in database.\n";
    }
} catch (Exception $e) {
    echo "Error accessing database: " . $e->getMessage() . "\n";
}

echo "\n=== EXPECTED RESULT ===\n";
echo "After import, your transaction name should be:\n";
echo "\"FATIMA K B K7A12W7R10N13 STO\"\n";
echo "Length: " . strlen("FATIMA K B K7A12W7R10N13 STO") . " characters\n";
?>
