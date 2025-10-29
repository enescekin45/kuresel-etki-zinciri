<?php
/**
 * User Registration API Endpoint
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
    $requiredFields = ['first_name', 'last_name', 'email', 'password', 'user_type'];
    foreach ($requiredFields as $field) {
        if (empty($input[$field])) {
            throw new Exception("Field '{$field}' is required", 400);
        }
    }
    
    // Validate email format
    if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format', 400);
    }
    
    // Validate password strength
    if (strlen($input['password']) < 8) {
        throw new Exception('Password must be at least 8 characters long', 400);
    }
    
    // Validate user type
    $validUserTypes = ['company', 'validator', 'consumer', 'admin'];
    if (!in_array($input['user_type'], $validUserTypes)) {
        throw new Exception('Invalid user type', 400);
    }
    
    // Get database instance
    $db = Database::getInstance();
    
    // Check if email already exists
    $sql = "SELECT COUNT(*) as count FROM users WHERE email = ?";
    $result = $db->fetchRow($sql, [$input['email']]);
    
    if ($result['count'] > 0) {
        throw new Exception('Email already exists', 400);
    }
    
    // Create user
    $user = new User();
    $userData = [
        'first_name' => $input['first_name'],
        'last_name' => $input['last_name'],
        'email' => $input['email'],
        'password' => $input['password'],
        'user_type' => $input['user_type'],
        'phone' => $input['phone'] ?? null
    ];
    
    // Create the user
    $userId = $user->create($userData);
    
    // Handle user type specific data
    switch ($input['user_type']) {
        case 'company':
            if (empty($input['company_name'])) {
                throw new Exception('Company name is required for company accounts', 400);
            }
            
            $sql = "INSERT INTO companies (uuid, user_id, company_name, company_type, industry_sector) VALUES (?, ?, ?, ?, ?)";
            $params = [
                $db->generateUUID(),
                $userId,
                $input['company_name'],
                $input['company_type'] ?? null,
                $input['industry_sector'] ?? null
            ];
            $db->insert($sql, $params);
            break;
            
        case 'validator':
            if (empty($input['validator_name'])) {
                throw new Exception('Organization name is required for validator accounts', 400);
            }
            
            $sql = "INSERT INTO validators (uuid, user_id, validator_name, organization_type, specialization) VALUES (?, ?, ?, ?, ?)";
            $params = [
                $db->generateUUID(),
                $userId,
                $input['validator_name'],
                $input['organization_type'] ?? null,
                !empty($input['specialization']) && is_array($input['specialization']) ? json_encode($input['specialization']) : null
            ];
            $db->insert($sql, $params);
            break;
            
        case 'consumer':
            // Store interests in user_preferences table
            if (!empty($input['interests']) && is_array($input['interests'])) {
                $sql = "INSERT INTO user_preferences (user_id, preference_key, preference_value) VALUES (?, ?, ?)";
                $db->insert($sql, [$userId, 'interests', json_encode($input['interests'])]);
            }
            break;
    }
    
    // Return success response
    $response = [
        'success' => true,
        'message' => 'User registered successfully',
        'data' => [
            'user_id' => $userId,
            'redirect' => '/Küresel/?page=login'
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