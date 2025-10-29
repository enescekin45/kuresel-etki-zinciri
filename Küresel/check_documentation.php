<?php
// Check documentation field for a product

// Define ROOT_DIR constant
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

// Include required files
require_once ROOT_DIR . '/config/database.php';
require_once ROOT_DIR . '/classes/Database.php';

try {
    $db = Database::getInstance();
    
    // Get documentation for a product
    $sql = "SELECT documentation FROM products WHERE product_code = 'PRD-GIDA-001'";
    $result = $db->fetchRow($sql);
    
    echo "Documentation field content:\n";
    echo $result['documentation'] . "\n";
    
    // Try to decode it
    $decoded = json_decode($result['documentation'], true);
    if ($decoded) {
        echo "\nDecoded certificates:\n";
        print_r($decoded);
    } else {
        echo "\nCould not decode JSON: " . json_last_error_msg() . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>