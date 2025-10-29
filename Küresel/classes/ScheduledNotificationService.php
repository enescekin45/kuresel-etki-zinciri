<?php
/**
 * Scheduled Notification Service
 * 
 * Handles sending scheduled notifications to users based on their preferences
 */

class ScheduledNotificationService {
    private $db;
    private $notificationService;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->notificationService = new NotificationService();
    }
    
    /**
     * Send scheduled notifications that are due
     */
    public function sendDueNotifications() {
        try {
            // Get notifications that are due to be sent
            $sql = "SELECT sn.*, u.email, u.first_name, u.last_name, u.phone, up.preference_value as notification_prefs
                    FROM scheduled_notifications sn
                    JOIN users u ON sn.user_id = u.id
                    LEFT JOIN user_preferences up ON u.id = up.user_id AND up.preference_key = 'scheduled_notifications'
                    WHERE sn.is_active = TRUE 
                    AND sn.next_send <= CURRENT_TIMESTAMP
                    AND u.status = 'active'";
            
            $notifications = $this->db->fetchAll($sql);
            
            $sentCount = 0;
            foreach ($notifications as $notification) {
                $this->sendNotification($notification);
                $sentCount++;
            }
            
            return $sentCount;
        } catch (Exception $e) {
            error_log("Error sending scheduled notifications: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Send a specific notification
     */
    private function sendNotification($notification) {
        try {
            // Create user object
            $user = new User();
            $user->loadById($notification['user_id']);
            
            // Determine notification type and content
            $notificationType = $notification['notification_type'];
            $subject = '';
            $message = '';
            
            switch ($notificationType) {
                case 'daily_summary':
                    $subject = 'Günlük Özet - Küresel Etki Zinciri';
                    $message = $this->generateDailySummary($user);
                    break;
                    
                case 'biweekly_summary':
                    $subject = '2 Haftalık Özet - Küresel Etki Zinciri';
                    $message = $this->generateBiweeklySummary($user);
                    break;
                    
                case 'weekly_update':
                    $subject = 'Haftalık Güncellemeler - Küresel Etki Zinciri';
                    $message = $this->generateWeeklyUpdate($user);
                    break;
            }
            
            // Send notification based on user preferences
            if (!empty($subject) && !empty($message)) {
                $this->notificationService->sendNotificationToUser($user, 'both', $subject, $message);
                
                // Update next send time
                $this->updateNextSendTime($notification['id'], $notificationType);
            }
            
        } catch (Exception $e) {
            error_log("Error sending notification to user {$notification['user_id']}: " . $e->getMessage());
        }
    }
    
    /**
     * Generate daily summary content
     */
    private function generateDailySummary($user) {
        $message = "Merhaba {$user->getFirstName()},\n\n";
        $message .= "Küresel Etki Zinciri hesabınız için günlük özet:\n\n";
        
        // In a real implementation, you would fetch actual data
        // For now, we'll generate sample content
        $message .= "📊 Hesap Özeti:\n";
        $message .= "- 3 yeni ürün tarandı\n";
        $message .= "- 120g karbon emisyonu tasarrufu sağlandı\n";
        $message .= "- 2 yeni şirket doğrulandı\n\n";
        
        $message .= "🌱 Sürdürülebilirlik Skoru: 8.2/10\n";
        $message .= "♻️ Bu hafta 5 geri dönüştürülmüş ürün seçtiniz\n\n";
        
        $message .= "Devam etmek için uygulamamızı ziyaret edin!\n";
        $message .= "https://kuresaletzinciri.com";
        
        return $message;
    }
    
    /**
     * Generate biweekly summary content
     */
    private function generateBiweeklySummary($user) {
        $message = "Merhaba {$user->getFirstName()},\n\n";
        $message .= "Küresel Etki Zinciri hesabınız için 2 haftalık özet:\n\n";
        
        // In a real implementation, you would fetch actual data
        $message .= "📈 2 Haftalık Performans:\n";
        $message .= "- 45 ürün tarandı\n";
        $message .= "- 1.2kg karbon emisyonu tasarrufu\n";
        $message .= "- 8 farklı sürdürülebilir marka keşfettiniz\n";
        $message .= "- 3 arkadaşınızla uygulamayı paylaştınız\n\n";
        
        $message .= "🏆 Başarılar:\n";
        $message .= "- Sürdürülebilir Tüketici rozetini kazandınız!\n";
        $message .= "- 1000 puan kazandınız\n\n";
        
        $message .= "Yeni özellikleri denemek için uygulamamızı ziyaret edin!\n";
        $message .= "https://kuresaletzinciri.com";
        
        return $message;
    }
    
    /**
     * Generate weekly update content
     */
    private function generateWeeklyUpdate($user) {
        $message = "Merhaba {$user->getFirstName()},\n\n";
        $message .= "Küresel Etki Zinciri haftalık güncellemeler:\n\n";
        
        // In a real implementation, you would fetch actual data
        $message .= "🔥 Yeni Özellikler:\n";
        $message .= "- Ürün karşılaştırma aracı yayınlandı\n";
        $message .= "- Yeni filtreleme seçenekleri eklendi\n\n";
        
        $message .= "📢 Kampanyalar:\n";
        $message .= "- Sürdürülebilir alışveriş haftası başlıyor!\n";
        $message .= "- Özel indirimler: %20'ye varan tasarruf\n\n";
        
        $message .= "Uygulamamızı ziyaret ederek yeni özellikleri keşfedin!\n";
        $message .= "https://kuresaletzinciri.com";
        
        return $message;
    }
    
    /**
     * Update next send time based on notification type
     */
    private function updateNextSendTime($notificationId, $notificationType) {
        $interval = '';
        switch ($notificationType) {
            case 'daily_summary':
                $interval = '1 DAY';
                break;
            case 'biweekly_summary':
                $interval = '14 DAY';
                break;
            case 'weekly_update':
                $interval = '7 DAY';
                break;
        }
        
        if (!empty($interval)) {
            $sql = "UPDATE scheduled_notifications 
                    SET last_sent = CURRENT_TIMESTAMP, 
                        next_send = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL $interval)
                    WHERE id = ?";
            $this->db->execute($sql, [$notificationId]);
        }
    }
    
    /**
     * Schedule notifications for a user
     */
    public function scheduleUserNotifications($userId, $notificationTypes = ['daily_summary']) {
        foreach ($notificationTypes as $type) {
            $sql = "INSERT IGNORE INTO scheduled_notifications (user_id, notification_type, next_send)
                    VALUES (?, ?, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 DAY))";
            $this->db->execute($sql, [$userId, $type]);
        }
    }
    
    /**
     * Update user notification preferences
     */
    public function updateUserPreferences($userId, $preferences) {
        // Save preferences to user_preferences table
        $userPrefs = new UserPreferences();
        $userPrefs->setPreference($userId, 'scheduled_notifications', json_encode($preferences));
        
        // Update scheduled notifications based on preferences
        $sql = "UPDATE scheduled_notifications 
                SET is_active = ?
                WHERE user_id = ? AND notification_type = ?";
        
        // Activate/deactivate based on preferences
        foreach (['daily_summary', 'biweekly_summary', 'weekly_update'] as $type) {
            $isActive = in_array($type, $preferences);
            $this->db->execute($sql, [$isActive ? 1 : 0, $userId, $type]);
        }
    }
    
    /**
     * Get user's scheduled notification preferences
     */
    public function getUserPreferences($userId) {
        $userPrefs = new UserPreferences();
        $prefs = $userPrefs->getPreference($userId, 'scheduled_notifications');
        
        if ($prefs) {
            return json_decode($prefs, true);
        }
        
        // Return default preferences
        return ['daily_summary']; // Default to daily summary
    }
}
?>