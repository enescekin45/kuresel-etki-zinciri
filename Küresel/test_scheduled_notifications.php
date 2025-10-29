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
    <title>Scheduled Notifications Test</title>
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
        .frequency-option { margin: 10px 0; padding: 10px; background: #f8f9fa; border-radius: 4px; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>📅 Scheduled Notifications System</h1>
        <p>This page demonstrates the scheduled notification system that sends regular updates to users.</p>";

try {
    $auth = Auth::getInstance();
    
    if (!$auth->isLoggedIn()) {
        echo "<div class='test-section'>
                <h2>Authentication Required</h2>
                <p class='error'>Please <a href='/Küresel/?page=login'>log in</a> to test the scheduled notification system.</p>
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
    
    // Load scheduled notification service
    $scheduledNotificationService = new ScheduledNotificationService();
    
    // Get current scheduled notification preferences
    echo "<div class='test-section'>
            <h2>🔔 Current Scheduled Notification Preferences</h2>";
    
    $userScheduledPrefs = $scheduledNotificationService->getUserPreferences($user->getId());
    
    if (!empty($userScheduledPrefs)) {
        echo "<p class='success'>✅ Scheduled notifications are ENABLED</p>";
        echo "<p><strong>Notification Types:</strong></p>";
        echo "<ul>";
        foreach ($userScheduledPrefs as $pref) {
            switch ($pref) {
                case 'daily_summary':
                    echo "<li>📅 Daily Summary (sent every day)</li>";
                    break;
                case 'biweekly_summary':
                    echo "<li>📅 Bi-weekly Summary (sent every 2 weeks)</li>";
                    break;
                case 'weekly_update':
                    echo "<li>📅 Weekly Updates (sent every week)</li>";
                    break;
            }
        }
        echo "</ul>";
    } else {
        echo "<p class='info'>ℹ️ Scheduled notifications are DISABLED</p>";
    }
    
    echo "</div>";
    
    // Show how scheduled notifications work
    echo "<div class='test-section'>
            <h2>📧 How Scheduled Notifications Work</h2>
            <div class='notification-example'>
                <h3>When you enable DAILY notifications:</h3>
                <p>✅ You will receive a daily summary email/SMS with your impact statistics</p>
            </div>
            
            <div class='notification-example'>
                <h3>When you enable BI-WEEKLY notifications:</h3>
                <p>✅ You will receive a detailed 2-week summary every 2 weeks</p>
            </div>
            
            <div class='notification-example'>
                <h3>When you enable WEEKLY notifications:</h3>
                <p>✅ You will receive weekly updates and new features information</p>
            </div>
          </div>";
    
    // Test form to change scheduled notification preferences
    echo "<div class='test-section'>
            <h2>⚙️ Configure Scheduled Notifications</h2>
            <form method='POST'>
                <div style='margin: 15px 0;'>
                    <label>
                        <input type='checkbox' name='enable_scheduled' " . (!empty($userScheduledPrefs) ? 'checked' : '') . ">
                        Enable Scheduled Notifications
                    </label>
                </div>
                
                <div id='frequency-options' style='display: " . (!empty($userScheduledPrefs) ? 'block' : 'none') . "; margin: 15px 0; padding: 15px; background: #f0f0f0; border-radius: 5px;'>
                    <h4>Select Notification Frequency:</h4>
                    <div class='frequency-option'>
                        <label>
                            <input type='radio' name='frequency' value='daily' " . (in_array('daily_summary', $userScheduledPrefs) ? 'checked' : 'checked') . ">
                            📅 Daily Summary (sent every day)
                        </label>
                    </div>
                    <div class='frequency-option'>
                        <label>
                            <input type='radio' name='frequency' value='biweekly' " . (in_array('biweekly_summary', $userScheduledPrefs) ? 'checked' : '') . ">
                            📅 Bi-weekly Summary (sent every 2 weeks)
                        </label>
                    </div>
                    <div class='frequency-option'>
                        <label>
                            <input type='radio' name='frequency' value='weekly' " . (in_array('weekly_update', $userScheduledPrefs) ? 'checked' : '') . ">
                            📅 Weekly Updates (sent every week)
                        </label>
                    </div>
                </div>
                
                <button type='submit' name='update_schedule' style='padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer;'>
                    Update Scheduled Notifications
                </button>
            </form>";
    
    if (isset($_POST['update_schedule'])) {
        $enableScheduled = isset($_POST['enable_scheduled']);
        $notificationTypes = [];
        
        if ($enableScheduled && isset($_POST['frequency'])) {
            switch ($_POST['frequency']) {
                case 'daily':
                    $notificationTypes = ['daily_summary'];
                    break;
                case 'biweekly':
                    $notificationTypes = ['biweekly_summary'];
                    break;
                case 'weekly':
                    $notificationTypes = ['weekly_update'];
                    break;
            }
        }
        
        // Update user preferences
        $scheduledNotificationService->updateUserPreferences($user->getId(), $notificationTypes);
        
        echo "<p class='success'>✅ Scheduled notification preferences updated successfully!</p>";
        
        if (!empty($notificationTypes)) {
            echo "<p class='info'>You will now receive " . implode(', ', $notificationTypes) . " notifications.</p>";
        } else {
            echo "<p class='info'>You have disabled scheduled notifications.</p>";
        }
    }
    
    echo "</div>";
    
    // Show database information
    echo "<div class='test-section'>
            <h2>🗄️ Database Information</h2>
            <p>The scheduled notifications are stored in the <code>scheduled_notifications</code> table:</p>
            <pre>
id | user_id | notification_type | last_sent | next_send | is_active
1  | 1       | daily_summary     | NULL      | " . date('Y-m-d H:i:s', strtotime('+1 day')) . " | 1
            </pre>
            <p>When the cron job runs, it checks for notifications where <code>next_send</code> is in the past and sends them.</p>
          </div>";
    
    // Instructions for setting up cron job
    echo "<div class='test-section'>
            <h2>⏰ Cron Job Setup</h2>
            <p>To enable scheduled notifications, set up a cron job to run every hour:</p>
            <pre>0 * * * * /usr/bin/php " . ROOT_DIR . "/cron/send_scheduled_notifications.php >> /var/log/scheduled_notifications.log 2>&1</pre>
            <p>Or on Windows, use Task Scheduler to run this command every hour:</p>
            <pre>php " . ROOT_DIR . "\\cron\\send_scheduled_notifications.php</pre>
          </div>";

} catch (Exception $e) {
    echo "<div class='test-section'>
            <h2>❌ Error</h2>
            <p class='error'>" . $e->getMessage() . "</p>
            <pre>" . $e->getTraceAsString() . "</pre>
          </div>";
}

echo "    </div>
    
    <script>
        // Toggle frequency options when enabling scheduled notifications
        document.addEventListener('DOMContentLoaded', function() {
            const enableCheckbox = document.querySelector('input[name=\"enable_scheduled\"]');
            const frequencyOptions = document.getElementById('frequency-options');
            
            if (enableCheckbox) {
                enableCheckbox.addEventListener('change', function() {
                    frequencyOptions.style.display = this.checked ? 'block' : 'none';
                });
            }
        });
    </script>
</body>
</html>";
?>