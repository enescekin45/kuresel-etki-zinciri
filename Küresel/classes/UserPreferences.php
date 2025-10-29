<?php
/**
 * User Preferences Class
 * 
 * Handles user preferences and settings
 */

class UserPreferences {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get default notification preferences
     */
    public static function getDefaultPreferences() {
        return [
            'email_notifications' => true,
            'sms_notifications' => false,
            'marketing_emails' => false
        ];
    }
    
    /**
     * Get user preference
     */
    public function getPreference($userId, $key) {
        $sql = "SELECT preference_value FROM user_preferences WHERE user_id = ? AND preference_key = ?";
        $result = $this->db->fetchRow($sql, [$userId, $key]);
        return $result ? $result['preference_value'] : null;
    }
    
    /**
     * Set user preference
     */
    public function setPreference($userId, $key, $value) {
        // Check if preference already exists
        $sql = "SELECT id FROM user_preferences WHERE user_id = ? AND preference_key = ?";
        $existing = $this->db->fetchRow($sql, [$userId, $key]);
        
        if ($existing) {
            // Update existing preference
            $sql = "UPDATE user_preferences SET preference_value = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
            $this->db->execute($sql, [$value, $existing['id']]);
        } else {
            // Insert new preference
            $sql = "INSERT INTO user_preferences (user_id, preference_key, preference_value) VALUES (?, ?, ?)";
            $this->db->insert($sql, [$userId, $key, $value]);
        }
        
        return true;
    }
    
    /**
     * Set multiple user preferences
     */
    public function setPreferences($userId, $preferences) {
        foreach ($preferences as $key => $value) {
            $this->setPreference($userId, $key, $value);
        }
        return true;
    }
    
    /**
     * Get all user preferences
     */
    public function getPreferences($userId) {
        $sql = "SELECT preference_key, preference_value FROM user_preferences WHERE user_id = ?";
        $results = $this->db->fetchAll($sql, [$userId]);
        
        $preferences = [];
        foreach ($results as $row) {
            $preferences[$row['preference_key']] = $row['preference_value'];
        }
        
        return $preferences;
    }
    
    /**
     * Delete user preference
     */
    public function deletePreference($userId, $key) {
        $sql = "DELETE FROM user_preferences WHERE user_id = ? AND preference_key = ?";
        $this->db->execute($sql, [$userId, $key]);
        return true;
    }
}
?>