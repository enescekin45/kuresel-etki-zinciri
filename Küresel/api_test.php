<?php
// Test the API endpoints

// Define constants
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

// Include required files
require_once ROOT_DIR . '/config/database.php';
require_once ROOT_DIR . '/classes/Database.php';
require_once ROOT_DIR . '/classes/Product.php';

try {
    echo "Testing API endpoints...\n\n";
    
    // Test 1: Get all products
    echo "1. Testing product listing...\n";
    $product = new Product();
    $products = $product->getAll(1, 10);
    echo "Found " . count($products) . " products\n\n";
    
    // Test 2: Get specific product by ID
    echo "2. Testing product detail retrieval...\n";
    if (count($products) > 0) {
        $firstProduct = $products[0];
        $productDetail = new Product();
        $productDetail->loadById($firstProduct['id']);
        $productData = $productDetail->toArray(true);
        echo "Retrieved product: " . $productData['product_name'] . " (ID: " . $productData['id'] . ")\n";
    }
    
    echo "\nAPI tests completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>