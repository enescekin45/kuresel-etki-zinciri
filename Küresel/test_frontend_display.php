<!DOCTYPE html>
<html>
<head>
    <title>Test Product Image Display</title>
</head>
<body>
    <h1>Testing Product Image Display</h1>
    
    <?php
    // Simulate what the frontend does
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
        
        echo "<h2>Product: " . $productData['product_name'] . "</h2>";
        echo "<p>Product ID: " . $productData['id'] . "</p>";
        
        if (!empty($productData['product_images'])) {
            $imagePath = $productData['product_images'][0];
            echo "<p>Image path from database: " . $imagePath . "</p>";
            
            // Check if file exists
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . $imagePath;
            echo "<p>Full path to check: " . $fullPath . "</p>";
            echo "<p>File exists: " . (file_exists($fullPath) ? "Yes" : "No") . "</p>";
            
            // Display the image
            echo "<h3>Image Display Test:</h3>";
            echo "<img src='" . $imagePath . "' alt='" . $productData['product_name'] . "' style='max-width: 300px; border: 1px solid #ccc;'>";
        } else {
            echo "<p>No images found for this product</p>";
        }
        
    } catch (Exception $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
    ?>
</body>
</html>