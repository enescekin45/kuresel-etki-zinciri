<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Define constants
define('ROOT_DIR', __DIR__);
define('FRONTEND_DIR', ROOT_DIR . '/frontend');
define('CLASSES_DIR', ROOT_DIR . '/classes');
define('CONFIG_DIR', ROOT_DIR . '/config');
define('API_DIR', ROOT_DIR . '/api');
define('BLOCKCHAIN_DIR', ROOT_DIR . '/blockchain');
define('UPLOADS_DIR', ROOT_DIR . '/uploads');
define('QR_CODES_DIR', ROOT_DIR . '/qr-codes');
define('LANG_DIR', ROOT_DIR . '/lang');

// Autoloader for classes
spl_autoload_register(function ($class_name) {
    $file = CLASSES_DIR . '/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Include configuration files
require_once CONFIG_DIR . '/database.php';
require_once CONFIG_DIR . '/blockchain.php';
require_once CONFIG_DIR . '/app.php';

// Initialize language system
$language = Language::getInstance();

// Set the language variable that the page expects
$lang = $language;

// Include the product page
require_once FRONTEND_DIR . '/pages/product.php';
?>