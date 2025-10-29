<?php
// Database installation script

// Load database configuration
$config = require 'config/database.php';
$dbConfig = $config['database'];

try {
    // Connect to MySQL
    $pdo = new PDO(
        "mysql:host={$dbConfig['host']};port={$dbConfig['port']};charset={$dbConfig['charset']}",
        $dbConfig['username'],
        $dbConfig['password'],
        $dbConfig['options']
    );
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS {$dbConfig['dbname']} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database created/verified successfully.\n";
    
    // Select the database
    $pdo->exec("USE {$dbConfig['dbname']}");
    
    // Read and execute the schema file
    $schema = file_get_contents('database/install_safe.sql');
    $pdo->exec($schema);
    
    echo "Database schema installed successfully.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>