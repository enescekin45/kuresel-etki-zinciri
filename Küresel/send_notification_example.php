<?php
// Example of how to send notifications in the application
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

// Function to send a notification to a user
function sendUserNotification($userId, $subject, $message) {
    try {
        // Load user
        $user = new User();
        $user->loadById($userId);
        
        // Send notification based on user preferences
        $notificationService = new NotificationService();
        $result = $notificationService->sendNotificationToUser($user, 'both', $subject, $message);
        
        return $result;
    } catch (Exception $e) {
        error_log("Failed to send notification: " . $e->getMessage());
        return false;
    }
}

// Example usage
echo "<h1>Notification Sending Example</h1>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_notification'])) {
    // Send a test notification
    $auth = Auth::getInstance();
    if ($auth->isLoggedIn()) {
        $currentUser = $auth->getCurrentUser();
        
        $subject = $_POST['subject'] ?? 'Test Notification';
        $message = $_POST['message'] ?? 'This is a test notification.';
        
        $result = sendUserNotification($currentUser->getId(), $subject, $message);
        
        if ($result) {
            echo "<p style='color: green;'>✅ Notification sent successfully!</p>";
        } else {
            echo "<p style='color: red;'>❌ Failed to send notification.</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ User not logged in.</p>";
    }
}

echo "
<form method='POST'>
    <div>
        <label>Subject:</label><br>
        <input type='text' name='subject' value='Account Update' style='width: 100%; padding: 8px; margin: 5px 0;'>
    </div>
    <div>
        <label>Message:</label><br>
        <textarea name='message' style='width: 100%; height: 100px; padding: 8px; margin: 5px 0;'>Your account settings have been updated successfully.</textarea>
    </div>
    <div>
        <button type='submit' name='send_notification' style='padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer;'>Send Notification</button>
    </div>
</form>

<h2>How It Works</h2>
<p>When you click 'Send Notification':</p>
<ol>
    <li>The system checks your notification preferences</li>
    <li>If email notifications are enabled, an email is sent to your email address</li>
    <li>If SMS notifications are enabled and you have a phone number, an SMS is sent to your phone</li>
    <li>The notification respects your preferences - you only receive notifications through channels you've selected</li>
</ol>

<h2>Test Your Preferences</h2>
<p>Go to <a href='/Küresel/?page=settings'>Settings</a> to update your notification preferences, then come back here to test them.</p>
";
?>