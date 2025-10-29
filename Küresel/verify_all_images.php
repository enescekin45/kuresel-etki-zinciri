<?php
// Verify all product images in the database and check if they exist on the filesystem

// Define required constants
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

// Include required files
require_once CONFIG_DIR . '/database.php';
require_once CLASSES_DIR . '/Database.php';

try {
    // Get database instance
    $dbConfig = require CONFIG_DIR . '/database.php';
    $db = Database::getInstance();
    
    echo "=== Product Image Verification ===\n\n";
    
    // Get all products with images
    $sql = "SELECT id, product_name, product_images FROM products WHERE product_images IS NOT NULL AND product_images != ''";
    $products = $db->fetchAll($sql);
    
    $totalProducts = count($products);
    $workingImages = 0;
    $brokenImages = 0;
    
    foreach ($products as $product) {
        echo "Product: " . $product['product_name'] . " (ID: " . $product['id'] . ")\n";
        
        // Decode the product images
        $images = json_decode($product['product_images'], true);
        
        if (!is_array($images) || empty($images)) {
            echo "  ❌ No valid images found\n";
            $brokenImages++;
            echo "\n";
            continue;
        }
        
        foreach ($images as $imagePath) {
            echo "  Image path: " . $imagePath . "\n";
            
            // FIXED: Construct the correct file path with /Küresel prefix for web access
            $webPath = '/Küresel' . $imagePath;
            echo "  Web path: " . $webPath . "\n";
            
            // Check if file exists on filesystem
            $fullPath = ROOT_DIR . $imagePath;
            $fileExists = file_exists($fullPath);
            
            if ($fileExists) {
                echo "  ✅ File exists on filesystem: " . $fullPath . "\n";
                $workingImages++;
            } else {
                echo "  ❌ File NOT found on filesystem: " . $fullPath . "\n";
                $brokenImages++;
            }
        }
        echo "\n";
    }
    
    echo "=== Summary ===\n";
    echo "Total products with images: " . $totalProducts . "\n";
    echo "Working images: " . $workingImages . "\n";
    echo "Broken images: " . $brokenImages . "\n";
    echo "Success rate: " . ($totalProducts > 0 ? round(($workingImages / ($workingImages + $brokenImages)) * 100, 2) : 0) . "%\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>