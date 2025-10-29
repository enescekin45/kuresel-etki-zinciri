<?php
/**
 * Test dashboard API endpoints
 */

define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

require_once CLASSES_DIR . '/Auth.php';

session_start();

$auth = Auth::getInstance();

if (!$auth->isLoggedIn()) {
    echo "User is not logged in.\n";
    exit(1);
}

echo "User is logged in.\n";
echo "User ID: " . $auth->getCurrentUser()->getId() . "\n";
echo "Is Validator: " . ($auth->isValidator() ? 'Yes' : 'No') . "\n";

// Test the stats endpoint by including it directly
echo "\nTesting stats endpoint:\n";
$_SERVER['REQUEST_METHOD'] = 'GET';

ob_start();
include 'api/v1/validator/stats/total.php';
$output = ob_get_clean();

echo "Response: " . $output . "\n";
?>