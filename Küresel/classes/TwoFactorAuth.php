<?php
/**
 * Two-Factor Authentication Class
 * 
 * Handles two-factor authentication for users
 */

class TwoFactorAuth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Check if 2FA is enabled for user
     */
    public function isEnabled($userId) {
        $sql = "SELECT is_enabled FROM user_2fa WHERE user_id = ?";
        $result = $this->db->fetchRow($sql, [$userId]);
        return $result ? (bool)$result['is_enabled'] : false;
    }
    
    /**
     * Enable 2FA for user
     */
    public function enable($userId, $secret) {
        // Check if 2FA record already exists
        $sql = "SELECT id FROM user_2fa WHERE user_id = ?";
        $existing = $this->db->fetchRow($sql, [$userId]);
        
        if ($existing) {
            // Update existing record
            $sql = "UPDATE user_2fa SET secret = ?, is_enabled = 1, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
            $this->db->execute($sql, [$secret, $existing['id']]);
        } else {
            // Insert new record
            $sql = "INSERT INTO user_2fa (user_id, secret, is_enabled) VALUES (?, ?, 1)";
            $this->db->insert($sql, [$userId, $secret]);
        }
        
        return true;
    }
    
    /**
     * Disable 2FA for user
     */
    public function disable($userId) {
        $sql = "UPDATE user_2fa SET is_enabled = 0, updated_at = CURRENT_TIMESTAMP WHERE user_id = ?";
        $this->db->execute($sql, [$userId]);
        return true;
    }
    
    /**
     * Get 2FA secret for user
     */
    public function getSecret($userId) {
        $sql = "SELECT secret FROM user_2fa WHERE user_id = ? AND is_enabled = 1";
        $result = $this->db->fetchRow($sql, [$userId]);
        return $result ? $result['secret'] : null;
    }
    
    /**
     * Generate backup codes
     */
    public function generateBackupCodes($count = 10) {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(bin2hex(random_bytes(6)));
        }
        return $codes;
    }
    
    /**
     * Save backup codes for user
     */
    public function saveBackupCodes($userId, $codes) {
        $sql = "UPDATE user_2fa SET backup_codes = ? WHERE user_id = ?";
        $this->db->execute($sql, [json_encode($codes), $userId]);
        return true;
    }
    
    /**
     * Validate backup code
     */
    public function validateBackupCode($userId, $code) {
        $sql = "SELECT backup_codes FROM user_2fa WHERE user_id = ?";
        $result = $this->db->fetchRow($sql, [$userId]);
        
        if (!$result || !$result['backup_codes']) {
            return false;
        }
        
        $codes = json_decode($result['backup_codes'], true);
        $index = array_search($code, $codes);
        
        if ($index !== false) {
            // Remove used code
            unset($codes[$index]);
            $this->saveBackupCodes($userId, array_values($codes));
            return true;
        }
        
        return false;
    }
}
?>