<?php
// Debug login issue

// Define project root directory
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

// Start session
session_start();

echo "Debug Login Information:\n";
echo "======================\n";

// Check session data
echo "Session Data:\n";
print_r($_SESSION);
echo "\n";

// Check if user is logged in
$auth = Auth::getInstance();
echo "Is Logged In: " . ($auth->isLoggedIn() ? 'Yes' : 'No') . "\n";

if ($auth->isLoggedIn()) {
    $user = $auth->getCurrentUser();
    if ($user) {
        echo "User ID: " . $user->getId() . "\n";
        echo "User Email: " . $user->getEmail() . "\n";
        echo "User Type: " . $user->getUserType() . "\n";
        echo "User Status: " . $user->getStatus() . "\n";
    } else {
        echo "User object is null\n";
    }
} else {
    echo "User is not logged in\n";
}

echo "\nServer Variables:\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "\n";
echo "QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'Not set') . "\n";
?>