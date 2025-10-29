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
require_once CLASSES_DIR . '/Auth.php';
require_once CLASSES_DIR . '/User.php';

// Start session
session_start();

// Check current session state
echo "Session data:\n";
print_r($_SESSION);

echo "\nAuth check:\n";
$auth = Auth::getInstance();

echo "Is logged in: " . ($auth->isLoggedIn() ? "Yes" : "No") . "\n";

if ($auth->isLoggedIn()) {
    echo "User ID: " . $auth->getCurrentUserId() . "\n";
    echo "User type: " . $auth->getCurrentUserType() . "\n";
    
    if ($auth->isCompany()) {
        echo "User is a company\n";
        $user = $auth->getCurrentUser();
        if ($user) {
            echo "User name: " . $user->getFullName() . "\n";
        }
    } else {
        echo "User is NOT a company\n";
    }
} else {
    echo "User is not logged in\n";
}
?>