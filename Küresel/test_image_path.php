<?php
// Test script to check image path handling

// Include required files like the API does
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Product.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $product = new Product();
    
    // Get a specific product to test
    $product->loadById(1); // Assuming ID 1 exists
    
    // Get product data
    $productData = $product->toArray(true); // Get detailed data
    
    if ($productData) {
        // Check what's in the product_images field
        echo "Product data:\n";
        var_dump($productData);
        
        echo "\nProduct images data:\n";
        var_dump($productData['product_images']);
    } else {
        echo "Product not found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>