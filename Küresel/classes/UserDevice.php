<?php
/**
 * User Device Class
 * 
 * Handles user device tracking and management
 */

class UserDevice {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Register current device for user
     */
    public function registerCurrentDevice($userId, $sessionId = null) {
        // Get device information
        $deviceName = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $deviceType = $this->detectDeviceType();
        $deviceOs = $this->detectOperatingSystem();
        $browser = $this->detectBrowser();
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        
        // Check if device already registered
        $sql = "SELECT id FROM user_devices WHERE user_id = ? AND session_id = ?";
        $existing = $this->db->fetchRow($sql, [$userId, $sessionId]);
        
        if ($existing) {
            // Update existing device
            $sql = "UPDATE user_devices SET 
                    device_name = ?, 
                    device_type = ?, 
                    device_os = ?, 
                    browser = ?, 
                    ip_address = ?, 
                    is_current = 1, 
                    last_activity = CURRENT_TIMESTAMP 
                    WHERE id = ?";
            $this->db->execute($sql, [$deviceName, $deviceType, $deviceOs, $browser, $ipAddress, $existing['id']]);
        } else {
            // Insert new device
            $sql = "INSERT INTO user_devices (user_id, device_name, device_type, device_os, browser, ip_address, session_id, is_current) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
            $this->db->insert($sql, [$userId, $deviceName, $deviceType, $deviceOs, $browser, $ipAddress, $sessionId]);
        }
        
        // Mark other devices as not current
        $sql = "UPDATE user_devices SET is_current = 0 WHERE user_id = ? AND session_id != ?";
        $this->db->execute($sql, [$userId, $sessionId]);
        
        return true;
    }
    
    /**
     * Get user devices
     */
    public function getUserDevices($userId) {
        $sql = "SELECT * FROM user_devices WHERE user_id = ? ORDER BY last_activity DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }
    
    /**
     * Get current device
     */
    public function getCurrentDevice($userId) {
        $sql = "SELECT * FROM user_devices WHERE user_id = ? AND is_current = 1";
        return $this->db->fetchRow($sql, [$userId]);
    }
    
    /**
     * Update device activity
     */
    public function updateActivity($deviceId) {
        $sql = "UPDATE user_devices SET last_activity = CURRENT_TIMESTAMP WHERE id = ?";
        $this->db->execute($sql, [$deviceId]);
        return true;
    }
    
    /**
     * Remove device
     */
    public function removeDevice($deviceId, $userId) {
        $sql = "DELETE FROM user_devices WHERE id = ? AND user_id = ?";
        $this->db->execute($sql, [$deviceId, $userId]);
        return true;
    }
    
    /**
     * Detect device type
     */
    private function detectDeviceType() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        if (preg_match('/mobile/i', $userAgent)) {
            return 'Mobile';
        } elseif (preg_match('/tablet/i', $userAgent)) {
            return 'Tablet';
        } else {
            return 'Desktop';
        }
    }
    
    /**
     * Detect operating system
     */
    private function detectOperatingSystem() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        if (preg_match('/windows/i', $userAgent)) {
            return 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            return 'Mac OS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            return 'Linux';
        } elseif (preg_match('/android/i', $userAgent)) {
            return 'Android';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            return 'iOS';
        } else {
            return 'Unknown';
        }
    }
    
    /**
     * Detect browser
     */
    private function detectBrowser() {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        if (preg_match('/chrome/i', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            return 'Firefox';
        } elseif (preg_match('/safari/i', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/edge/i', $userAgent)) {
            return 'Edge';
        } elseif (preg_match('/opera/i', $userAgent)) {
            return 'Opera';
        } else {
            return 'Unknown';
        }
    }
}
?>