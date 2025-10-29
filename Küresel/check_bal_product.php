<?php
// Check the exact image path stored for the "bal" product

// Define required constants
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

// Include required files
require_once CONFIG_DIR . '/database.php';
require_once CLASSES_DIR . '/Database.php';
require_once CLASSES_DIR . '/Product.php';

try {
    // Get database instance
    $db = Database::getInstance();
    
    echo "=== Checking Bal Product (ID 3) ===\n";
    
    // Get the product directly from database
    $sql = "SELECT id, product_name, product_images FROM products WHERE id = 3";
    $product = $db->fetchRow($sql);
    
    if ($product) {
        echo "Product Name: " . $product['product_name'] . "\n";
        echo "Raw product_images from DB: " . $product['product_images'] . "\n";
        
        // Decode the JSON
        $images = json_decode($product['product_images'], true);
        echo "Decoded images array: " . json_encode($images) . "\n";
        
        if (is_array($images) && !empty($images)) {
            $imagePath = $images[0];
            echo "First image path: " . $imagePath . "\n";
            
            // Check if file exists
            $fullPath = ROOT_DIR . $imagePath;
            echo "Full file path: " . $fullPath . "\n";
            echo "File exists: " . (file_exists($fullPath) ? "Yes" : "No") . "\n";
            
            // Check with web path
            $webPath = '/Küresel' . $imagePath;
            echo "Web path: " . $webPath . "\n";
        }
    } else {
        echo "Bal product not found!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>