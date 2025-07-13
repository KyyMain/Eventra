<?php
// Test password update functionality
require_once 'vendor/autoload.php';

// Load CodeIgniter
$app = require_once 'app/Config/Paths.php';
$app = new \CodeIgniter\CodeIgniter($app);
$app->initialize();

use App\Models\UserModel;

$userModel = new UserModel();

echo "=== Password Update Test ===\n";

try {
    // Get a test user (assuming user ID 1 exists)
    $user = $userModel->find(1);
    if (!$user) {
        echo "✗ No user found with ID 1\n";
        exit;
    }
    
    echo "✓ Found user: " . $user['username'] . " (" . $user['email'] . ")\n";
    echo "Current password hash: " . substr($user['password'], 0, 20) . "...\n";
    
    // Test password update
    $newPassword = 'TestPassword123!';
    $updateData = [
        'password' => $newPassword
    ];
    
    echo "\nTesting password update...\n";
    echo "New password: $newPassword\n";
    
    $result = $userModel->update(1, $updateData);
    
    if ($result) {
        echo "✓ Update operation returned success\n";
        
        // Verify the password was actually changed
        $updatedUser = $userModel->find(1);
        echo "New password hash: " . substr($updatedUser['password'], 0, 20) . "...\n";
        
        if ($user['password'] !== $updatedUser['password']) {
            echo "✓ Password hash changed successfully\n";
            
            // Test password verification
            if (password_verify($newPassword, $updatedUser['password'])) {
                echo "✓ Password verification successful\n";
                echo "=== TEST PASSED ===\n";
            } else {
                echo "✗ Password verification failed\n";
                echo "=== TEST FAILED ===\n";
            }
        } else {
            echo "✗ Password hash did not change\n";
            echo "=== TEST FAILED ===\n";
        }
    } else {
        echo "✗ Update operation failed\n";
        echo "=== TEST FAILED ===\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "=== TEST FAILED ===\n";
}
?>