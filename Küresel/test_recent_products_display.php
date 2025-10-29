<?php
// Define required constants
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', __DIR__);
}

if (!defined('CONFIG_DIR')) {
    define('CONFIG_DIR', ROOT_DIR . '/config');
}

if (!defined('CLASSES_DIR')) {
    define('CLASSES_DIR', ROOT_DIR . '/classes');
}

// Include required files
require_once CONFIG_DIR . '/database.php';
require_once CLASSES_DIR . '/Database.php';
require_once CLASSES_DIR . '/Company.php';

try {
    // Load a company (assuming company ID 4 exists)
    $company = new Company();
    $company->loadById(4);
    
    // Get recent products for this company
    $products = $company->getProducts(1, 10);
    
    echo "<h2>Recent Products Test</h2>\n";
    echo "<p>Company: " . $company->getCompanyName() . "</p>\n";
    echo "<p>Total recent products: " . count($products) . "</p>\n";
    
    if (!empty($products)) {
        echo "<h3>Recent Products:</h3>\n";
        foreach ($products as $product) {
            echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>\n";
            echo "<p><strong>Product Name:</strong> " . htmlspecialchars($product['product_name']) . "</p>\n";
            echo "<p><strong>Product Code:</strong> " . htmlspecialchars($product['product_code']) . "</p>\n";
            echo "<p><strong>Category:</strong> " . htmlspecialchars($product['category']) . "</p>\n";
            echo "<p><strong>Status:</strong> " . htmlspecialchars($product['status']) . "</p>\n";
            echo "<p><strong>Created At:</strong> " . htmlspecialchars($product['created_at']) . "</p>\n";
            
            // Display product images
            if (!empty($product['product_images']) && is_array($product['product_images'])) {
                echo "<p><strong>Product Images:</strong></p>\n";
                echo "<ul>\n";
                foreach ($product['product_images'] as $image) {
                    if (is_string($image)) {
                        echo "<li>Image path: " . htmlspecialchars($image) . "</li>\n";
                    } elseif (is_array($image) && isset($image['url'])) {
                        echo "<li>Image URL: " . htmlspecialchars($image['url']) . "</li>\n";
                    }
                }
                echo "</ul>\n";
            } else {
                echo "<p><strong>Product Images:</strong> None</p>\n";
            }
            
            echo "</div>\n";
        }
    } else {
        echo "<p>No recent products found for this company.</p>\n";
    }
    
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>\n";
}
?>