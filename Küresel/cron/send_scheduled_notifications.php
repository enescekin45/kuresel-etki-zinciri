<?php
/**
 * Cron job script to send scheduled notifications
 * 
 * This script should be run periodically (e.g., every hour) to send scheduled notifications
 */

// Define base directories
define('ROOT_DIR', dirname(dirname(__FILE__)));
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
require_once CONFIG_DIR . '/app.php';

try {
    echo "Starting scheduled notifications process...\n";
    
    // Initialize the scheduled notification service
    $scheduledNotificationService = new ScheduledNotificationService();
    
    // Send due notifications
    $sentCount = $scheduledNotificationService->sendDueNotifications();
    
    echo "Sent {$sentCount} scheduled notifications.\n";
    echo "Process completed successfully.\n";
    
} catch (Exception $e) {
    error_log("Error in scheduled notifications cron job: " . $e->getMessage());
    echo "Error: " . $e->getMessage() . "\n";
}
?>