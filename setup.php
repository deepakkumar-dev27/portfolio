<?php
/**
 * Database Setup Script for Portfolio Website
 * Run this script once to set up the database and tables
 */

require_once 'config/database.php';

echo "<h2>Portfolio Database Setup</h2>";

try {
    // Create database connection
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    echo "<p>✅ Database '" . DB_NAME . "' created successfully.</p>";
    
    // Use the database
    $pdo->exec("USE " . DB_NAME);
    
    // Create contacts table
    $contactsTable = "
    CREATE TABLE IF NOT EXISTS contacts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status ENUM('unread', 'read') DEFAULT 'unread',
        INDEX idx_status (status),
        INDEX idx_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($contactsTable);
    echo "<p>✅ Contacts table created successfully.</p>";
    
    // Create admin_users table
    $adminTable = "
    CREATE TABLE IF NOT EXISTS admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($adminTable);
    echo "<p>✅ Admin users table created successfully.</p>";
    
    // Insert default admin user (password: admin123)
    $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $insertAdmin = "
    INSERT INTO admin_users (username, password, email) 
    VALUES ('admin', :password, 'admin@portfolio.com')
    ON DUPLICATE KEY UPDATE username = username";
    
    $stmt = $pdo->prepare($insertAdmin);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();
    echo "<p>✅ Default admin user created (username: admin, password: admin123).</p>";
    
    echo "<h3>🎉 Setup Complete!</h3>";
    echo "<p><strong>Next steps:</strong></p>";
    echo "<ul>";
    echo "<li>Your portfolio is ready at: <a href='index.html'>index.html</a></li>";
    echo "<li>Admin panel is available at: <a href='admin/login.php'>admin/login.php</a></li>";
    echo "<li>Default admin credentials: <strong>admin</strong> / <strong>admin123</strong></li>";
    echo "<li>Contact form will save submissions to the database</li>";
    echo "</ul>";
    
    echo "<h3>🔧 Configuration</h3>";
    echo "<p>Database settings in <code>config/database.php</code>:</p>";
    echo "<ul>";
    echo "<li>Host: " . DB_HOST . "</li>";
    echo "<li>Database: " . DB_NAME . "</li>";
    echo "<li>User: " . DB_USER . "</li>";
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p><strong>Please check your database configuration in config/database.php</strong></p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Portfolio Setup</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        h2 { color: #333; }
        h3 { color: #149ddd; }
        p { margin: 10px 0; }
        ul { margin: 10px 0 10px 20px; }
        code { background: #f5f5f5; padding: 2px 5px; border-radius: 3px; }
        a { color: #149ddd; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body></body>
</html>
