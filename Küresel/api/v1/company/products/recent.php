<?php
/**
 * Company Recent Products API Endpoint
 */

// Define required constants only if not already defined
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', __DIR__ . '/../../../../..');
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

try {
    // Get the Auth instance
    $auth = Auth::getInstance();
    
    // Check if user is logged in
    if (!$auth->isLoggedIn()) {
        throw new Exception('User not logged in', 401);
    }
    
    // Check if user is a company
    if (!$auth->isCompany()) {
        throw new Exception('Access denied. Company access required.', 403);
    }
    
    // Get current user
    $user = $auth->getCurrentUser();
    
    // Load company
    $company = new Company();
    $company->loadByUserId($user->getId());
    
    // Get recent products (last 10)
    $products = $company->getRecentProducts(10);
    
    // Process product images for each product
    foreach ($products as &$product) {
        // Decode product_images if it's a JSON string
        if (isset($product['product_images']) && is_string($product['product_images'])) {
            $product['product_images'] = json_decode($product['product_images'], true) ?: [];
        } elseif (!isset($product['product_images'])) {
            $product['product_images'] = [];
        }
    }
    
    // Return success response
    $response = [
        'success' => true,
        'data' => $products
    ];
    
} catch (Exception $e) {
    $statusCode = $e->getCode() ?: 400;
    http_response_code($statusCode);
    
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => $statusCode
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>