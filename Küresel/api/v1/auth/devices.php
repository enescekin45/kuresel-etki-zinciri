<?php
/**
 * User Devices API Endpoint
 * 
 * Handles getting user device information
 */

header('Content-Type: application/json');

try {
    // Check if user is logged in
    $auth = Auth::getInstance();
    if (!$auth->isLoggedIn()) {
        throw new Exception('User not authenticated', 401);
    }
    
    $currentUser = $auth->getCurrentUser();
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            // Get user devices
            $devices = $currentUser->getDevices();
            
            echo json_encode([
                'success' => true,
                'data' => $devices
            ]);
            break;
            
        default:
            throw new Exception('Method not allowed', 405);
    }
    
} catch (Exception $e) {
    $statusCode = $e->getCode() ?: 500;
    if ($statusCode >= 400 && $statusCode < 600) {
        http_response_code($statusCode);
    } else {
        http_response_code(500);
    }
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>