<?php
/**
 * User Profile API Endpoint
 * 
 * Handles updating user profile information
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
        case 'POST':
        case 'PUT':
            // Update user profile
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                throw new Exception('Invalid JSON data', 400);
            }
            
            // Validate and sanitize input
            $allowedFields = ['first_name', 'last_name', 'email', 'phone', 'language'];
            $profileData = [];
            
            foreach ($allowedFields as $field) {
                if (isset($input[$field]) && !empty($input[$field])) {
                    $profileData[$field] = trim($input[$field]);
                }
            }
            
            // Update profile
            if (!empty($profileData)) {
                $currentUser->update($profileData);
                
                // Send notification about profile update
                $notificationService = new NotificationService();
                $notificationService->sendNotificationToUser(
                    $currentUser,
                    'both', // Send based on user preferences
                    'Profil Güncellendi',
                    'Profil bilgileriniz başarıyla güncellendi. Bu değişiklik güvenlik nedeniyle yapılmıştır.'
                );
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'first_name' => $currentUser->getFirstName(),
                    'last_name' => $currentUser->getLastName(),
                    'email' => $currentUser->getEmail(),
                    'phone' => $currentUser->getPhone(),
                    'language' => $currentUser->getLanguage()
                ]
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