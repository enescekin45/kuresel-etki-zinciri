<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define constants
define('ROOT_DIR', __DIR__);
define('FRONTEND_DIR', ROOT_DIR . '/frontend');
define('CLASSES_DIR', ROOT_DIR . '/classes');

// Autoloader for classes
spl_autoload_register(function ($class_name) {
    $file = CLASSES_DIR . '/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Test if we can load the product page
echo "Testing product page load...\n";

// Initialize language system (if it exists)
$lang = null;
if (file_exists(CLASSES_DIR . '/Language.php')) {
    $lang = Language::getInstance();
    echo "Language system initialized\n";
} else {
    echo "Language system not found\n";
}

// Try to include the product page
$page_file = FRONTEND_DIR . '/pages/product.php';
if (file_exists($page_file)) {
    echo "Product page file found\n";
    // Include the product page
    include $page_file;
} else {
    echo "Product page file not found: " . $page_file . "\n";
}
?>