<?php
// Final test to verify notification system works end-to-end
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
    <title>Final Notification System Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        .warning { color: orange; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 3px; overflow-x: auto; }
        .notification-example { background: #e8f4fd; padding: 15px; border-left: 4px solid #007cba; margin: 10px 0; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>✅ Final Notification System Test</h1>
        <p>This test verifies that the notification system works end-to-end as requested.</p>";

try {
    $auth = Auth::getInstance();
    
    if (!$auth->isLoggedIn()) {
        echo "<div class='test-section'>
                <h2>Authentication Required</h2>
                <p class='error'>Please <a href='/Küresel/?page=login'>log in</a> to test the notification system.</p>
              </div>";
        exit;
    }
    
    $user = $auth->getCurrentUser();
    echo "<div class='test-section'>
            <h2>👤 User Information</h2>
            <p><strong>Email:</strong> " . $user->getEmail() . "</p>
            <p><strong>Name:</strong> " . $user->getFullName() . "</p>
            <p><strong>Phone:</strong> " . ($user->getPhone() ?: "Not set") . "</p>
          </div>";
    
    // Get current notification preferences
    echo "<div class='test-section'>
            <h2>🔔 Current Notification Preferences</h2>";
    
    $preferences = $user->getPreferences();
    $defaultPrefs = UserPreferences::getDefaultPreferences();
    $mergedPrefs = array_merge($defaultPrefs, $preferences);
    
    echo "<p><strong>Preferences:</strong></p>
          <pre>" . print_r($mergedPrefs, true) . "</pre>";
    
    echo "</div>";
    
    // Show how notifications work
    echo "<div class='test-section'>
            <h2>📧 How Notifications Work</h2>
            <div class='notification-example'>
                <h3>When you select EMAIL notifications and save:</h3>
                <p>✅ You will receive email notifications at: <strong>" . $user->getEmail() . "</strong></p>
            </div>
            
            <div class='notification-example'>
                <h3>When you select SMS notifications and save:</h3>
                <p>✅ You will receive SMS notifications at: <strong>" . ($user->getPhone() ?: "No phone number set") . "</strong></p>
            </div>
            
            <div class='notification-example'>
                <h3>When you select BOTH email and SMS:</h3>
                <p>✅ You will receive notifications through BOTH channels</p>
            </div>
            
            <div class='notification-example'>
                <h3>When you disable both:</h3>
                <p>✅ You will NOT receive any notifications</p>
            </div>
          </div>";
    
    // Test sending notifications
    echo "<div class='test-section'>
            <h2>🚀 Test Notification Sending</h2>";
    
    $notificationService = new NotificationService();
    
    // Show what would be sent based on current preferences
    echo "<h3>Based on your current preferences:</h3>";
    
    if ($mergedPrefs['email_notifications']) {
        echo "<p class='success'>📧 Email notification WILL be sent to: " . $user->getEmail() . "</p>";
    } else {
        echo "<p class='info'>📧 Email notifications are DISABLED</p>";
    }
    
    if ($mergedPrefs['sms_notifications'] && $user->getPhone()) {
        echo "<p class='success'>📱 SMS notification WILL be sent to: " . $user->getPhone() . "</p>";
    } elseif ($mergedPrefs['sms_notifications'] && !$user->getPhone()) {
        echo "<p class='warning'>📱 SMS notifications are ENABLED but you have no phone number set</p>";
    } else {
        echo "<p class='info'>📱 SMS notifications are DISABLED</p>";
    }
    
    echo "<h3>Try it now:</h3>
          <form method='POST' style='margin: 15px 0;'>
              <button type='submit' name='send_test' style='padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer;'>
                  Send Test Notification
              </button>
          </form>";
    
    if (isset($_POST['send_test'])) {
        $result = $notificationService->sendNotificationToUser(
            $user,
            'both',
            'Test Bildirimi',
            'Bu, bildirim sistemini test etmek için gönderilen bir test bildirimidir. Tercihlerinize göre e-posta veya SMS olarak ulaşmış olmalıdır.'
        );
        
        if ($result) {
            echo "<p class='success'>✅ Test notification sent successfully!</p>";
            echo "<p class='info'>Check the error log to see what would be sent (emails and SMS are logged, not actually sent in this demo).</p>";
        } else {
            echo "<p class='error'>❌ Failed to send test notification.</p>";
        }
    }
    
    echo "</div>";
    
    // Instructions
    echo "<div class='test-section'>
            <h2>📋 How to Test</h2>
            <ol>
                <li>Go to <a href='/Küresel/?page=settings'>Settings</a> page</li>
                <li>Toggle your notification preferences (email, SMS, marketing)</li>
                <li>Click 'Tercihleri Kaydet' (Save Preferences)</li>
                <li>Come back to this page and click 'Send Test Notification'</li>
                <li>Check the error log to see what would be sent</li>
            </ol>
          </div>";
    
    // Show error log location
    echo "<div class='test-section'>
            <h2>📝 Check Notification Logs</h2>
            <p>To see what notifications would be sent, check your PHP error log:</p>
            <pre>" . ini_get('error_log') . "</pre>
            <p>In the log, you'll see entries like:</p>
            <pre>EMAIL SENT - To: user@example.com, Subject: Test Bildirimi, Message: ...
SMS SENT - To: +1234567890, Message: ...</pre>
          </div>";

} catch (Exception $e) {
    echo "<div class='test-section'>
            <h2>❌ Error</h2>
            <p class='error'>" . $e->getMessage() . "</p>
            <pre>" . $e->getTraceAsString() . "</pre>
          </div>";
}

echo "    </div>
</body>
</html>";
?>