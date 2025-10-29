<?php
require_once 'config/bootstrap.php';

// Test notification preferences endpoints
try {
    $auth = Auth::getInstance();
    
    if (!$auth->isLoggedIn()) {
        echo "User not logged in. Please log in first.\n";
        exit;
    }
    
    $user = $auth->getCurrentUser();
    echo "Testing notification preferences for user: " . $user->getEmail() . "\n";
    
    // Test getting preferences
    $preferences = $user->getPreferences();
    echo "Current preferences: " . json_encode($preferences, JSON_PRETTY_PRINT) . "\n";
    
    // Test setting preferences
    $newPreferences = [
        'email_notifications' => true,
        'sms_notifications' => true,
        'marketing_emails' => false
    ];
    
    $user->setPreferences($newPreferences);
    echo "Preferences updated successfully\n";
    
    // Test getting updated preferences
    $updatedPreferences = $user->getPreferences();
    echo "Updated preferences: " . json_encode($updatedPreferences, JSON_PRETTY_PRINT) . "\n";
    
    echo "Test completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>