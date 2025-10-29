<?php
/**
 * Scheduled Notifications API Endpoint
 * 
 * Handles getting and setting user scheduled notification preferences
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
            // Get user scheduled notification preferences
            $scheduledNotificationService = new ScheduledNotificationService();
            $preferences = $scheduledNotificationService->getUserPreferences($currentUser->getId());
            
            echo json_encode([
                'success' => true,
                'data' => $preferences
            ]);
            break;
            
        case 'POST':
            // Update user scheduled notification preferences
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                throw new Exception('Invalid JSON data', 400);
            }
            
            $scheduledNotifications = $input['scheduled_notifications'] ?? false;
            $notificationTypes = $input['notification_types'] ?? [];
            
            // Update preferences
            $scheduledNotificationService = new ScheduledNotificationService();
            
            if ($scheduledNotifications && !empty($notificationTypes)) {
                // Enable scheduled notifications
                $scheduledNotificationService->updateUserPreferences($currentUser->getId(), $notificationTypes);
            } else {
                // Disable scheduled notifications
                $scheduledNotificationService->updateUserPreferences($currentUser->getId(), []);
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Scheduled notification preferences updated successfully',
                'data' => $notificationTypes
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