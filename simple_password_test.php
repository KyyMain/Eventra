<?php
// Simple password hash test
$testPassword = 'TestPassword123!';

echo "=== Password Hash Test ===\n";
echo "Test password: $testPassword\n";

// Test password hashing
$hashedPassword = password_hash($testPassword, PASSWORD_DEFAULT);
echo "Hashed password: " . substr($hashedPassword, 0, 30) . "...\n";

// Test password verification
if (password_verify($testPassword, $hashedPassword)) {
    echo "✓ Password verification successful\n";
} else {
    echo "✗ Password verification failed\n";
}

// Test database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'eventra_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Database connection successful\n";
    
    // Test direct password update
    $userId = 1;
    $newHashedPassword = password_hash('NewTestPassword123!', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
    $result = $stmt->execute([$newHashedPassword, $userId]);
    
    if ($result) {
        echo "✓ Direct password update successful\n";
        
        // Verify the update
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if ($user && password_verify('NewTestPassword123!', $user['password'])) {
            echo "✓ Password update verification successful\n";
            echo "=== TEST PASSED ===\n";
        } else {
            echo "✗ Password update verification failed\n";
            echo "=== TEST FAILED ===\n";
        }
    } else {
        echo "✗ Direct password update failed\n";
        echo "=== TEST FAILED ===\n";
    }
    
} catch (PDOException $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
    echo "=== TEST FAILED ===\n";
}
?>