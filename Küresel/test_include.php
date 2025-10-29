<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define constants
define('ROOT_DIR', __DIR__);
define('FRONTEND_DIR', ROOT_DIR . '/frontend');
define('CLASSES_DIR', ROOT_DIR . '/classes');
define('CONFIG_DIR', ROOT_DIR . '/config');

// Autoloader for classes
spl_autoload_register(function ($class_name) {
    $file = CLASSES_DIR . '/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Start session
session_start();

// Set page to product to test
$page = 'product';

echo "Attempting to load page: " . $page . "\n";

// Handle frontend requests based on page parameter
if ($page === 'product') {
    echo "Including product page...\n";
    // Start output buffering to catch any errors
    ob_start();
    try {
        include FRONTEND_DIR . '/pages/product.php';
        $content = ob_get_contents();
        ob_end_clean();
        echo "Product page included successfully\n";
        echo "Content length: " . strlen($content) . " characters\n";
    } catch (Exception $e) {
        ob_end_clean();
        echo "Error including product page: " . $e->getMessage() . "\n";
    }
} else {
    echo "Page is not product\n";
}
?>