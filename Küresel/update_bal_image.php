<?php
// Update the bal product to use the simpler SVG image

// Define required constants
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

// Include required files
require_once CONFIG_DIR . '/database.php';
require_once CLASSES_DIR . '/Database.php';

try {
    // Get database instance
    $db = Database::getInstance();
    
    echo "=== Updating Bal Product Image ===\n";
    
    // Update the product_images field for the "bal" product (ID 3)
    $newImagePath = '["\\/assets\\/images\\/products\\/bal_simple.svg"]';
    
    $sql = "UPDATE products SET product_images = ? WHERE id = 3";
    $db->execute($sql, [$newImagePath]);
    
    echo "Updated bal product to use bal_simple.svg\n";
    
    // Verify the update
    $sql = "SELECT product_images FROM products WHERE id = 3";
    $result = $db->fetchRow($sql);
    
    echo "New product_images value: " . $result['product_images'] . "\n";
    
    echo "Update completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>