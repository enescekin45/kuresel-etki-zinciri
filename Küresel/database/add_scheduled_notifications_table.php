<?php
/**
 * Add scheduled_notifications table
 */

define('ROOT_DIR', dirname(__DIR__));
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

require_once CLASSES_DIR . '/Database.php';

try {
    $db = Database::getInstance();
    
    $sql = "CREATE TABLE IF NOT EXISTS scheduled_notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        notification_type ENUM('daily_summary', 'biweekly_summary', 'weekly_update') NOT NULL,
        last_sent TIMESTAMP NULL DEFAULT NULL,
        next_send TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_user_id (user_id),
        INDEX idx_notification_type (notification_type),
        INDEX idx_next_send (next_send),
        INDEX idx_is_active (is_active)
    ) ENGINE=InnoDB";
    
    $db->execute($sql);
    
    echo "✅ scheduled_notifications table created successfully!\n";
    
    // Add a sample notification schedule for existing users
    $sql = "INSERT IGNORE INTO scheduled_notifications (user_id, notification_type, next_send)
            SELECT id, 'daily_summary', DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 DAY)
            FROM users 
            WHERE status = 'active'";
    
    $db->execute($sql);
    
    echo "✅ Sample notification schedules added for existing users!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>