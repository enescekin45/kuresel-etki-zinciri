<?php
/**
 * Notification Preferences API Endpoint
 * 
 * Handles getting and setting user notification preferences
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
            // Get user notification preferences
            $preferences = $currentUser->getPreferences();
            
            // Merge with default preferences to ensure all keys are present
            $defaultPrefs = UserPreferences::getDefaultPreferences();
            $userPrefs = array_merge($defaultPrefs, $preferences);
            
            echo json_encode([
                'success' => true,
                'data' => $userPrefs
            ]);
            break;
            
        case 'POST':
            // Update user notification preferences
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                throw new Exception('Invalid JSON data', 400);
            }
            
            // Validate and sanitize input
            $allowedKeys = ['email_notifications', 'sms_notifications', 'marketing_emails'];
            $preferencesToUpdate = [];
            
            foreach ($allowedKeys as $key) {
                if (isset($input[$key])) {
                    // Convert to boolean
                    $value = filter_var($input[$key], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    if ($value !== null) {
                        $preferencesToUpdate[$key] = $value;
                    }
                }
            }
            
            // Update preferences
            if (!empty($preferencesToUpdate)) {
                $currentUser->setPreferences($preferencesToUpdate);
                
                // Send confirmation notification about preference changes
                $notificationService = new NotificationService();
                $message = "Bildirim tercihleriniz güncellendi:\n";
                $message .= "- E-posta bildirimleri: " . ($preferencesToUpdate['email_notifications'] ? 'Aktif' : 'Pasif') . "\n";
                $message .= "- SMS bildirimleri: " . (isset($preferencesToUpdate['sms_notifications']) && $preferencesToUpdate['sms_notifications'] ? 'Aktif' : 'Pasif') . "\n";
                $message .= "- Pazarlama e-postaları: " . (isset($preferencesToUpdate['marketing_emails']) && $preferencesToUpdate['marketing_emails'] ? 'Aktif' : 'Pasif');
                
                $notificationService->sendNotificationToUser(
                    $currentUser,
                    'both', // Send based on (possibly updated) user preferences
                    'Bildirim Tercihleri Güncellendi',
                    $message
                );
            }
            
            // Get updated preferences
            $updatedPreferences = $currentUser->getPreferences();
            $defaultPrefs = UserPreferences::getDefaultPreferences();
            $userPrefs = array_merge($defaultPrefs, $updatedPreferences);
            
            echo json_encode([
                'success' => true,
                'message' => 'Notification preferences updated successfully',
                'data' => $userPrefs
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