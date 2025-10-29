<?php
require_once 'config/app.php';

// Define base directories
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

// Autoloader for classes
spl_autoload_register(function ($class_name) {
    $file = CLASSES_DIR . '/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Include configuration files
require_once CONFIG_DIR . '/database.php';

// Start session
session_start();

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Notification Sending Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 3px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Notification Sending Test</h1>";

try {
    $auth = Auth::getInstance();
    
    if (!$auth->isLoggedIn()) {
        echo "<div class='test-section'>
                <h2>Authentication Status</h2>
                <p class='error'>User not logged in. Please <a href='/Küresel/?page=login'>log in</a> first.</p>
              </div>";
        exit;
    }
    
    echo "<div class='test-section'>
            <h2>Authentication Status</h2>
            <p class='success'>User is logged in</p>
          </div>";
    
    $user = $auth->getCurrentUser();
    echo "<div class='test-section'>
            <h2>User Information</h2>
            <p><strong>Email:</strong> " . $user->getEmail() . "</p>
            <p><strong>Name:</strong> " . $user->getFullName() . "</p>
            <p><strong>Phone:</strong> " . ($user->getPhone() ?: "Not set") . "</p>
          </div>";
    
    // Test current notification preferences
    echo "<div class='test-section'>
            <h2>Current Notification Preferences</h2>";
    
    $preferences = $user->getPreferences();
    $defaultPrefs = UserPreferences::getDefaultPreferences();
    $mergedPrefs = array_merge($defaultPrefs, $preferences);
    
    echo "<p><strong>Preferences:</strong></p>
          <pre>" . print_r($mergedPrefs, true) . "</pre>";
    
    echo "</div>";
    
    // Test sending notifications
    echo "<div class='test-section'>
            <h2>Send Test Notifications</h2>";
    
    $notificationService = new NotificationService();
    
    // Test sending notification based on user preferences
    echo "<h3>Testing Notification Based on User Preferences</h3>";
    $result = $notificationService->sendNotificationToUser(
        $user,
        'both', // Send both email and SMS based on preferences
        'Test Notification',
        'This is a test notification to verify the notification system is working correctly.'
    );
    
    if ($result) {
        echo "<p class='success'>✅ Notification sending process completed successfully</p>";
        
        // Show what would be sent based on preferences
        if ($mergedPrefs['email_notifications']) {
            echo "<p class='info'>📧 Email notification would be sent to: " . $user->getEmail() . "</p>";
        } else {
            echo "<p class='info'>📧 Email notifications are disabled for this user</p>";
        }
        
        if ($mergedPrefs['sms_notifications'] && $user->getPhone()) {
            echo "<p class='info'>📱 SMS notification would be sent to: " . $user->getPhone() . "</p>";
        } elseif ($mergedPrefs['sms_notifications'] && !$user->getPhone()) {
            echo "<p class='info'>📱 SMS notifications are enabled but user has no phone number set</p>";
        } else {
            echo "<p class='info'>📱 SMS notifications are disabled for this user</p>";
        }
    } else {
        echo "<p class='error'>❌ Failed to send notification</p>";
    }
    
    echo "</div>";
    
    // Test form to change preferences and send notification
    echo "<div class='test-section'>
            <h2>Change Preferences and Send Notification</h2>
            <form method='POST'>
                <div style='margin: 10px 0;'>
                    <label>
                        <input type='checkbox' name='email_notifications' " . ($mergedPrefs['email_notifications'] ? 'checked' : '') . ">
                        Email Notifications
                    </label>
                </div>
                <div style='margin: 10px 0;'>
                    <label>
                        <input type='checkbox' name='sms_notifications' " . ($mergedPrefs['sms_notifications'] ? 'checked' : '') . ">
                        SMS Notifications
                    </label>
                </div>
                <div style='margin: 10px 0;'>
                    <label>
                        <input type='checkbox' name='marketing_emails' " . ($mergedPrefs['marketing_emails'] ? 'checked' : '') . ">
                        Marketing Emails
                    </label>
                </div>
                <div style='margin: 10px 0;'>
                    <label>
                        Phone Number: <input type='text' name='phone' value='" . htmlspecialchars($user->getPhone() ?? '') . "'>
                    </label>
                </div>
                <button type='submit' name='action' value='update_prefs'>Update Preferences</button>
                <button type='submit' name='action' value='send_test'>Send Test Notification</button>
            </form>";
    
    if ($_POST && isset($_POST['action'])) {
        if ($_POST['action'] === 'update_prefs') {
            // Update preferences
            $newPreferences = [
                'email_notifications' => isset($_POST['email_notifications']),
                'sms_notifications' => isset($_POST['sms_notifications']),
                'marketing_emails' => isset($_POST['marketing_emails'])
            ];
            
            $user->setPreferences($newPreferences);
            
            // Update phone number if provided
            if (isset($_POST['phone']) && !empty($_POST['phone'])) {
                $user->update(['phone' => $_POST['phone']]);
            }
            
            echo "<p class='success'>✅ Preferences updated successfully</p>";
            
            // Refresh user data
            $user = $auth->getCurrentUser();
            $preferences = $user->getPreferences();
            $mergedPrefs = array_merge($defaultPrefs, $preferences);
            
            echo "<p><strong>Updated preferences:</strong></p>
                  <pre>" . print_r($mergedPrefs, true) . "</pre>";
        } elseif ($_POST['action'] === 'send_test') {
            // Send test notification
            $notificationService = new NotificationService();
            $result = $notificationService->sendNotificationToUser(
                $user,
                'both',
                'Test Notification After Preference Change',
                'This is a test notification sent after updating your preferences.'
            );
            
            echo "<p class='success'>✅ Test notification sent based on updated preferences</p>";
        }
    }
    
    echo "</div>";
    
    echo "<div class='test-section'>
            <h2>How It Works</h2>
            <p>When you:</p>
            <ol>
                <li>Enable email notifications and save preferences → You'll receive email notifications</li>
                <li>Enable SMS notifications, set your phone number, and save preferences → You'll receive SMS notifications</li>
                <li>Enable both → You'll receive notifications through both channels</li>
                <li>Disable both → You won't receive any notifications</li>
            </ol>
            <p>The system respects your preferences and only sends notifications through the channels you've selected.</p>
          </div>";

} catch (Exception $e) {
    echo "<div class='test-section'>
            <h2>Error</h2>
            <p class='error'>" . $e->getMessage() . "</p>
            <pre>" . $e->getTraceAsString() . "</pre>
          </div>";
}

echo "</body>
</html>";
?>