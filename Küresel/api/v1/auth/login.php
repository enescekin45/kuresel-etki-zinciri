<?php
/**
 * User Login API Endpoint
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
require_once CLASSES_DIR . '/User.php';
require_once CLASSES_DIR . '/Auth.php';

try {
    // Get the Auth instance
    $auth = Auth::getInstance();
    
    // Check if user is already logged in
    if ($auth->isLoggedIn()) {
        throw new Exception('User is already logged in', 400);
    }
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    if (empty($input['email']) || empty($input['password'])) {
        throw new Exception('Email and password are required', 400);
    }
    
    // Validate email format
    if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format', 400);
    }
    
    // Authenticate user
    if (!$auth->login($input['email'], $input['password'])) {
        throw new Exception('Invalid email or password', 401);
    }
    
    // Get current user
    $user = $auth->getCurrentUser();
    
    // Update last login
    $db = Database::getInstance();
    $sql = "UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?";
    $db->execute($sql, [$user->getId()]);
    
    // Return success response with user data
    $response = [
        'success' => true,
        'message' => 'Login successful',
        'data' => [
            'user' => [
                'id' => $user->getId(),
                'uuid' => $user->getUuid(),
                'email' => $user->getEmail(),
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
                'full_name' => $user->getFullName(),
                'user_type' => $user->getUserType(),
                'status' => $user->getStatus(),
                'phone' => $user->getPhone(),
                'profile_image' => $user->getProfileImage()
            ]
        ]
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