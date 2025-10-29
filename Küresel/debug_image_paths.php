<?php
// Debug script to check image paths and file accessibility

// Define required constants
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

try {
    // Get database instance
    $db = Database::getInstance();
    $product = new Product();
    
    // Test with "Bal" product (ID 3)
    echo "=== Testing Bal Product (ID 3) ===\n";
    $product->loadById(3);
    $productData = $product->toArray(true);
    
    echo "Product Name: " . $productData['product_name'] . "\n";
    echo "Raw product_images from DB: " . json_encode($productData['product_images']) . "\n";
    
    // Process images like the API does
    if (isset($productData['product_images'])) {
        if (is_string($productData['product_images'])) {
            $productData['product_images'] = json_decode($productData['product_images'], true) ?: [];
        } elseif (!is_array($productData['product_images'])) {
            $productData['product_images'] = [];
        }
    } else {
        $productData['product_images'] = [];
    }
    
    echo "Processed product_images: " . json_encode($productData['product_images']) . "\n";
    
    if (!empty($productData['product_images'])) {
        $imagePath = $productData['product_images'][0];
        echo "Image path: " . $imagePath . "\n";
        
        // Check if file exists from web root perspective
        $webRoot = $_SERVER['DOCUMENT_ROOT'] ?? 'c:\xampp\htdocs';
        $fullPath = $webRoot . $imagePath;
        echo "Web root: " . $webRoot . "\n";
        echo "Full path to check: " . $fullPath . "\n";
        echo "File exists: " . (file_exists($fullPath) ? "Yes" : "No") . "\n";
        
        // Also check with forward slashes
        $fullPathFixed = str_replace('/', DIRECTORY_SEPARATOR, $webRoot . $imagePath);
        echo "Full path (fixed): " . $fullPathFixed . "\n";
        echo "File exists (fixed): " . (file_exists($fullPathFixed) ? "Yes" : "No") . "\n";
    }
    
    echo "\n";
    
    // Test with "Organik Zeytinyağı" product (ID 2)
    echo "=== Testing Organik Zeytinyağı Product (ID 2) ===\n";
    $product->loadById(2);
    $productData = $product->toArray(true);
    
    echo "Product Name: " . $productData['product_name'] . "\n";
    echo "Raw product_images from DB: " . json_encode($productData['product_images']) . "\n";
    
    // Process images like the API does
    if (isset($productData['product_images'])) {
        if (is_string($productData['product_images'])) {
            $productData['product_images'] = json_decode($productData['product_images'], true) ?: [];
        } elseif (!is_array($productData['product_images'])) {
            $productData['product_images'] = [];
        }
    } else {
        $productData['product_images'] = [];
    }
    
    echo "Processed product_images: " . json_encode($productData['product_images']) . "\n";
    
    if (!empty($productData['product_images'])) {
        $imagePath = $productData['product_images'][0];
        echo "Image path: " . $imagePath . "\n";
        
        // Check if file exists from web root perspective
        $webRoot = $_SERVER['DOCUMENT_ROOT'] ?? 'c:\xampp\htdocs';
        $fullPath = $webRoot . $imagePath;
        echo "Web root: " . $webRoot . "\n";
        echo "Full path to check: " . $fullPath . "\n";
        echo "File exists: " . (file_exists($fullPath) ? "Yes" : "No") . "\n";
        
        // Also check with forward slashes
        $fullPathFixed = str_replace('/', DIRECTORY_SEPARATOR, $webRoot . $imagePath);
        echo "Full path (fixed): " . $fullPathFixed . "\n";
        echo "File exists (fixed): " . (file_exists($fullPathFixed) ? "Yes" : "No") . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>