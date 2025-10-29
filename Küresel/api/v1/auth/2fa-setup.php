<?php
/**
 * 2FA Setup API Endpoint
 * 
 * Handles getting and setting user 2FA preferences
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
            // Get user 2FA status
            $status = $currentUser->get2FAStatus();
            
            echo json_encode([
                'success' => true,
                'data' => $status
            ]);
            break;
            
        case 'DELETE':
            // Disable 2FA
            $twoFactorAuth = new TwoFactorAuth();
            $result = $twoFactorAuth->disable2FA($currentUser->getId());
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Two-factor authentication disabled successfully'
                ]);
            } else {
                throw new Exception('Failed to disable two-factor authentication');
            }
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