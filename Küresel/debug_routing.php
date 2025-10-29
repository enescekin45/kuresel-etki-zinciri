<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define constants
define('ROOT_DIR', __DIR__);
define('FRONTEND_DIR', ROOT_DIR . '/frontend');
define('CLASSES_DIR', ROOT_DIR . '/classes');
define('CONFIG_DIR', ROOT_DIR . '/config');
define('API_DIR', ROOT_DIR . '/api');

// Autoloader for classes
spl_autoload_register(function ($class_name) {
    $file = CLASSES_DIR . '/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Start session
session_start();

// Simulate the routing logic
$page = isset($_GET['page']) ? $_GET['page'] : null;

echo "Page parameter: " . ($page ?? 'null') . "\n";

if (!$page) {
    // Try URL-based routing
    $request_uri = $_SERVER['REQUEST_URI'];
    $path = parse_url($request_uri, PHP_URL_PATH);
    
    echo "Request URI: " . $request_uri . "\n";
    echo "Path: " . $path . "\n";
    
    // Handle both encoded and non-encoded paths
    $decoded_path = urldecode($path);
    echo "Decoded path: " . $decoded_path . "\n";
    
    $path = str_replace(['/Küresel', '/K%C3%BCresel'], '', $decoded_path); // Remove base directory (both encoded and decoded)
    echo "Cleaned path: " . $path . "\n";
    
    // Convert path to page parameter
    if ($path === '/' || $path === '' || $path === '/index.php') {
        $page = 'home';
    } elseif ($path === '/product') {
        $page = 'product';
    }
    
    echo "Determined page: " . ($page ?? 'null') . "\n";
}

// Handle frontend requests based on page parameter
if ($page === 'product') {
    echo "Loading product page...\n";
    // Product profile page
    include FRONTEND_DIR . '/pages/product.php';
} else {
    echo "Page is not product, it's: " . ($page ?? 'null') . "\n";
}
?>