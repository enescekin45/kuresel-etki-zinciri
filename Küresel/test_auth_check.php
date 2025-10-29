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

// Check if we have a session
echo "Session status: " . session_status() . "\n";
echo "Session ID: " . session_id() . "\n";

// Check session data
echo "Session data:\n";
print_r($_SESSION);

// Test Auth class
$auth = Auth::getInstance();
echo "Auth instance created\n";

echo "Is logged in: " . ($auth->isLoggedIn() ? "Yes" : "No") . "\n";
echo "Current user ID: " . $auth->getCurrentUserId() . "\n";
echo "Current user type: " . $auth->getCurrentUserType() . "\n";
echo "Is company: " . ($auth->isCompany() ? "Yes" : "No") . "\n";

if ($auth->isLoggedIn()) {
    $user = $auth->getCurrentUser();
    if ($user) {
        echo "User loaded: " . $user->getFullName() . "\n";
    }
}
?>