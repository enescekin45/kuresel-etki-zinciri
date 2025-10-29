<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define constants
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

// Autoloader for classes
spl_autoload_register(function ($class_name) {
    $file = CLASSES_DIR . '/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Include configuration files
$config = require_once CONFIG_DIR . '/database.php';

echo "Database configuration:\n";
print_r($config['database']);

try {
    // Test database connection
    $dsn = "mysql:host={$config['database']['host']};port={$config['database']['port']};dbname={$config['database']['dbname']};charset={$config['database']['charset']}";
    $pdo = new PDO($dsn, $config['database']['username'], $config['database']['password'], $config['database']['options']);
    
    echo "Database connection successful!\n";
    
    // Test a simple query
    $stmt = $pdo->query("SELECT VERSION() as version");
    $result = $stmt->fetch();
    echo "MySQL version: " . $result['version'] . "\n";
    
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}
?>