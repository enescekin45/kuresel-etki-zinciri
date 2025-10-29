<?php
/**
 * Company QR Scans Statistics API Endpoint
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
    
    // Get QR scan count
    $db = Database::getInstance();
    $sql = "SELECT COUNT(*) as scan_count 
            FROM qr_scans qs
            JOIN products p ON qs.product_id = p.id
            WHERE p.company_id = ?";
    $result = $db->fetchRow($sql, [$company->getId()]);
    $scanCount = $result['scan_count'] ?? 0;
    
    // Return success response
    $response = [
        'success' => true,
        'data' => $scanCount
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