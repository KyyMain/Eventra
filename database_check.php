<?php
// Simple database check script
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'eventra_db';

try {
    // Connect to MySQL server
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected to MySQL server\n";
    
    // Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE '$database'");
    if ($stmt->rowCount() == 0) {
        echo "✗ Database '$database' does not exist. Creating...\n";
        $pdo->exec("CREATE DATABASE $database CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        echo "✓ Database '$database' created\n";
    } else {
        echo "✓ Database '$database' exists\n";
    }
    
    // Connect to the specific database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() == 0) {
        echo "✗ Table 'users' does not exist. Creating...\n";
        
        $createUsersTable = "
        CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) UNIQUE NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(255) NOT NULL,
            phone VARCHAR(20) NULL,
            role ENUM('admin', 'user') DEFAULT 'user',
            avatar VARCHAR(255) NULL,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
        
        $pdo->exec($createUsersTable);
        echo "✓ Table 'users' created\n";
        
        // Insert default admin user
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $insertAdmin = "
        INSERT INTO users (username, email, password, full_name, role, is_active) 
        VALUES ('admin', 'admin@eventra.com', ?, 'Administrator', 'admin', 1)";
        
        $stmt = $pdo->prepare($insertAdmin);
        $stmt->execute([$adminPassword]);
        echo "✓ Default admin user created (admin/admin123)\n";
        
    } else {
        echo "✓ Table 'users' exists\n";
        
        // Check table structure
        $stmt = $pdo->query("DESCRIBE users");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "Current columns: " . implode(', ', $columns) . "\n";
        
        // Check if password column exists and has correct type
        $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'password'");
        if ($stmt->rowCount() > 0) {
            $passwordColumn = $stmt->fetch();
            echo "✓ Password column exists: " . $passwordColumn['Type'] . "\n";
        }
    }
    
    echo "\n=== Database Check Complete ===\n";
    
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>