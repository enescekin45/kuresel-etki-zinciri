<?php
// Get user details endpoint

// Define required constants only if not already defined
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', realpath(__DIR__ . '/../../../../..'));
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

header('Content-Type: application/json');

try {
    // Get the Auth instance
    $auth = Auth::getInstance();
    
    // Check if user is logged in
    if (!$auth->isLoggedIn()) {
        throw new Exception('User not logged in', 401);
    }
    
    // Check if user is an admin
    if (!$auth->getCurrentUser()->isAdmin()) {
        throw new Exception('Access denied. Admin access required.', 403);
    }
    
    // Get user ID from URL parameter
    $userId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($userId <= 0) {
        throw new Exception('Geçersiz kullanıcı ID', 400);
    }
    
    // Get database instance
    $db = Database::getInstance();
    
    // Fetch user details
    $sql = "SELECT u.id, u.first_name, u.last_name, u.email, u.user_type, u.status, u.created_at, 
                   c.company_name, v.specialization
            FROM users u
            LEFT JOIN companies c ON u.id = c.user_id
            LEFT JOIN validators v ON u.id = v.user_id
            WHERE u.id = ?";
    
    $user = $db->fetchRow($sql, [$userId]);
    
    if (!$user) {
        throw new Exception('Kullanıcı bulunamadı', 404);
    }
    
    // Return user details
    $response = [
        'success' => true,
        'user' => $user
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

echo json_encode($response);