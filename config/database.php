<?php
// Load environment variables
require_once __DIR__ . '/env_loader.php';

// Database configuration for remote hosting
// Use environment variables for security
define('DB_HOST', $_ENV['DB_HOST'] ?? 'your-remote-db-host.com');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'portfolio_db');
define('DB_USER', $_ENV['DB_USER'] ?? 'your_username');
define('DB_PASS', $_ENV['DB_PASS'] ?? 'your_password');
define('DB_PORT', $_ENV['DB_PORT'] ?? '3306');

class Database {
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $port = DB_PORT;
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Updated connection string with port and SSL options for remote DB
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // For cloud databases
            ];
            
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch(PDOException $exception) {
            error_log("Database connection error: " . $exception->getMessage());
            // Don't expose sensitive info in production
            if (isset($_ENV['ENVIRONMENT']) && $_ENV['ENVIRONMENT'] === 'development') {
                echo "Connection error: " . $exception->getMessage();
            } else {
                echo "Database connection failed. Please try again later.";
            }
        }
        return $this->conn;
    }
}
?>
