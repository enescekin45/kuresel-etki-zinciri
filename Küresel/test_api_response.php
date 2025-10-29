<?php
// Test what the API is actually returning

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
    
    // Load the "Organik Zeytinyağı" product (ID 2)
    $product->loadById(2);
    $productData = $product->toArray(true);
    
    echo "Product Data:\n";
    echo "Name: " . $productData['product_name'] . "\n";
    echo "Images (raw): " . json_encode($productData['product_images']) . "\n";
    
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
    
    echo "Images (processed): " . json_encode($productData['product_images']) . "\n";
    
    if (!empty($productData['product_images'])) {
        echo "First image path: " . $productData['product_images'][0] . "\n";
        
        // Check if file exists
        $imagePath = $_SERVER['DOCUMENT_ROOT'] . $productData['product_images'][0];
        echo "Full path to check: " . $imagePath . "\n";
        echo "File exists: " . (file_exists($imagePath) ? "Yes" : "No") . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Test API response directly
echo "<h1>Testing API Response</h1>";

// Capture the actual API response
ob_start();
try {
    // Include the API endpoint directly
    include 'api/v1/auth/notification-preferences.php';
    $apiResponse = ob_get_contents();
} catch (Exception $e) {
    $apiResponse = "Exception: " . $e->getMessage();
}
ob_end_clean();

echo "<h2>API Response:</h2>";
echo "<pre>" . htmlspecialchars($apiResponse) . "</pre>";

echo "<h2>Raw API Response:</h2>";
echo "<textarea style='width: 100%; height: 200px;'>" . $apiResponse . "</textarea>";

// Check if it's valid JSON
$jsonData = json_decode($apiResponse, true);
if ($jsonData === null) {
    echo "<p style='color: red;'>❌ Response is NOT valid JSON</p>";
    echo "<p>Error: " . json_last_error_msg() . "</p>";
} else {
    echo "<p style='color: green;'>✅ Response is valid JSON</p>";
    echo "<pre>" . print_r($jsonData, true) . "</pre>";
}
?>