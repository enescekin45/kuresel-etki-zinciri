<?php
/**
 * Validator Pending Validations API Endpoint
 */

// Define required constants only if not already defined
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', __DIR__ . '/../../../..');
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
require_once CLASSES_DIR . '/Validator.php';

try {
    // Get the Auth instance
    $auth = Auth::getInstance();
    
    // Check if user is logged in
    if (!$auth->isLoggedIn()) {
        throw new Exception('User not logged in', 401);
    }
    
    // Check if user is a validator
    if (!$auth->isValidator()) {
        throw new Exception('Access denied. Validator access required.', 403);
    }
    
    // Get current user
    $user = $auth->getCurrentUser();
    
    // Load validator
    $validator = new Validator();
    $validator->loadByUserId($user->getId());
    
    // Get pending validations (first 5 for dashboard)
    $pendingValidations = $validator->getPendingValidations(1, 5);
    
    // Format the data for the frontend
    $formattedValidations = [];
    foreach ($pendingValidations as $validation) {
        $formattedValidations[] = [
            'id' => $validation['id'],
            'product_name' => $validation['product_name'],
            'company_name' => $validation['company_name'],
            'validation_type' => $validation['validation_type'],
            'requested_at' => $validation['requested_at'],
            'priority' => 'normal' // This would be determined by business logic
        ];
    }
    
    // Return success response
    $response = [
        'success' => true,
        'data' => $formattedValidations
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