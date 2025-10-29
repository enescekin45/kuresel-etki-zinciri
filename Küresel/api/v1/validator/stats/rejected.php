<?php
/**
 * Validator Rejected Statistics API Endpoint
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
    
    // Get database instance
    $db = Database::getInstance();
    
    // Get rejected validations count
    $sql = "SELECT COUNT(*) as count FROM validation_records WHERE validator_id = ? AND validation_result = 'rejected'";
    $result = $db->fetchRow($sql, [$validator->getId()]);
    
    // Return success response
    $response = [
        'success' => true,
        'data' => $result['count'] ?? 0
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