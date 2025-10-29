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
require_once CLASSES_DIR . '/Company.php';
require_once CLASSES_DIR . '/User.php';

// Start session
session_start();

// Simulate a logged-in company user
// Set session data for a company user (user ID 20 based on our test)
$_SESSION['logged_in'] = true;
$_SESSION['user_id'] = 20; // This should be a user that is a company
$_SESSION['user_type'] = 'company';
$_SESSION['user_email'] = 'test@company.com';
$_SESSION['user_name'] = 'Test Company User';
$_SESSION['login_time'] = time();

echo "Simulated session data:\n";
print_r($_SESSION);

// Now test the recent products API logic
try {
    // Get the Auth instance
    $auth = Auth::getInstance();
    
    echo "\nAuth check:\n";
    echo "Is logged in: " . ($auth->isLoggedIn() ? "Yes" : "No") . "\n";
    echo "Is company: " . ($auth->isCompany() ? "Yes" : "No") . "\n";
    
    if ($auth->isLoggedIn() && $auth->isCompany()) {
        // Get current user
        $user = $auth->getCurrentUser();
        echo "Current user ID: " . $user->getId() . "\n";
        
        // Load company
        $company = new Company();
        $company->loadByUserId($user->getId());
        echo "Loaded company: " . $company->getCompanyName() . "\n";
        
        // Get recent products (last 10)
        $products = $company->getProducts(1, 10);
        echo "Found " . count($products) . " recent products\n";
        
        // Format response like the API would
        $response = [
            'success' => true,
            'data' => $products
        ];
        
        echo "\nResponse:\n";
        echo json_encode($response, JSON_PRETTY_PRINT);
    } else {
        echo "Authentication failed\n";
    }
    
} catch (Exception $e) {
    $statusCode = $e->getCode() ?: 400;
    
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => $statusCode
    ];
    
    echo "\nError response:\n";
    echo json_encode($response, JSON_PRETTY_PRINT);
}
?>