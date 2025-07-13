<?php
// Test strong password validation
require_once 'app/Validation/CustomRules.php';

use App\Validation\CustomRules;

$customRules = new CustomRules();

echo "=== Strong Password Validation Test ===\n";

$testPasswords = [
    'weak' => 'password',
    'short' => 'Pass1!',
    'no_upper' => 'password123!',
    'no_lower' => 'PASSWORD123!',
    'no_number' => 'Password!',
    'no_special' => 'Password123',
    'valid' => 'Password123!'
];

foreach ($testPasswords as $type => $password) {
    $error = null;
    $result = $customRules->strong_password($password, $error);
    
    echo "\nTesting '$type' password: '$password'\n";
    echo "Result: " . ($result ? "✓ VALID" : "✗ INVALID") . "\n";
    if (!$result && $error) {
        echo "Error: $error\n";
    }
}

echo "\n=== Test Complete ===\n";
?>