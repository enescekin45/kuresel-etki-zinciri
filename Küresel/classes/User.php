<?php
/**
 * User Model Class
 * 
 * Handles user management operations including authentication,
 * registration, and profile management
 */

class User {
    private $db;
    private $id;
    private $uuid;
    private $email;
    private $firstName;
    private $lastName;
    private $userType;
    private $status;
    private $createdAt;
    private $lastLogin;
    private $phone;
    private $profileImage;
    private $preferences;
    private $twoFactorAuth;
    private $devices;
    
    public function __construct() {
        $this->db = Database::getInstance();
        
        // Initialize optional dependencies only if classes exist
        if (class_exists('UserPreferences')) {
            $this->preferences = new UserPreferences();
        } else {
            $this->preferences = null;
        }
        
        if (class_exists('TwoFactorAuth')) {
            $this->twoFactorAuth = new TwoFactorAuth();
        } else {
            $this->twoFactorAuth = null;
        }
        
        if (class_exists('UserDevice')) {
            $this->devices = new UserDevice();
        } else {
            $this->devices = null;
        }
    }
    
    /**
     * Create a new user
     */
    public function create($data) {
        // Validate required fields
        $required = ['email', 'password', 'first_name', 'last_name', 'user_type'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Field '{$field}' is required");
            }
        }
        
        // Check if email already exists
        if ($this->emailExists($data['email'])) {
            throw new Exception("Email already exists");
        }
        
        // Hash password
        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
        
        // Generate UUID
        $uuid = $this->db->generateUUID();
        
        $sql = "INSERT INTO users (uuid, email, password_hash, first_name, last_name, 
                phone, user_type, status, language, timezone) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $uuid,
            $data['email'],
            $passwordHash,
            $data['first_name'],
            $data['last_name'],
            $data['phone'] ?? null,
            $data['user_type'],
            $data['status'] ?? 'active', // Changed from 'pending' to 'active' for immediate access
            $data['language'] ?? 'tr',
            $data['timezone'] ?? 'Europe/Istanbul'
        ];
        
        $userId = $this->db->insert($sql, $params);
        
        // Load the created user
        $this->loadById($userId);
        
        return $userId;
    }
    
    /**
     * Authenticate user with email and password
     */
    public function authenticate($email, $password) {
        $sql = "SELECT id, uuid, email, password_hash, first_name, last_name, 
                user_type, status FROM users WHERE email = ? AND status = 'active'";
        
        $user = $this->db->fetchRow($sql, [$email]);
        
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }
        
        // Update last login
        $this->updateLastLogin($user['id']);
        
        // Load user data
        $this->loadById($user['id']);
        
        return true;
    }
    
    /**
     * Load user by ID
     */
    public function loadById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $user = $this->db->fetchRow($sql, [$id]);
        
        if (!$user) {
            throw new Exception("User not found");
        }
        
        $this->populateFromArray($user);
        return $this;
    }
    
    /**
     * Load user by UUID
     */
    public function loadByUuid($uuid) {
        $sql = "SELECT * FROM users WHERE uuid = ?";
        $user = $this->db->fetchRow($sql, [$uuid]);
        
        if (!$user) {
            throw new Exception("User not found");
        }
        
        $this->populateFromArray($user);
        return $this;
    }
    
    /**
     * Load user by email
     */
    public function loadByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $user = $this->db->fetchRow($sql, [$email]);
        
        if (!$user) {
            throw new Exception("User not found");
        }
        
        $this->populateFromArray($user);
        return $this;
    }
    
    /**
     * Update user profile
     */
    public function update($data) {
        if (!$this->id) {
            throw new Exception("User not loaded");
        }
        
        $allowedFields = [
            'first_name', 'last_name', 'email', 'phone', 'profile_image', 
            'language', 'timezone', 'status'
        ];
        
        $updateFields = [];
        $params = [];
        
        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                // Special validation for email
                if ($field === 'email') {
                    // Check if email already exists for another user
                    $sql = "SELECT COUNT(*) as count FROM users WHERE email = ? AND id != ?";
                    $result = $this->db->fetchRow($sql, [$value, $this->id]);
                    if ($result['count'] > 0) {
                        throw new Exception("Email already exists");
                    }
                }
                
                $updateFields[] = "{$field} = ?";
                $params[] = $value;
            }
        }
        
        if (empty($updateFields)) {
            throw new Exception("No valid fields to update");
        }
        
        $params[] = $this->id;
        
        $sql = "UPDATE users SET " . implode(', ', $updateFields) . 
               ", updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        
        $this->db->execute($sql, $params);
        
        // Reload user data
        $this->loadById($this->id);
        
        return true;
    }
    
    /**
     * Change password
     */
    public function changePassword($currentPassword, $newPassword) {
        if (!$this->id) {
            throw new Exception("User not loaded");
        }
        
        // Verify current password
        $sql = "SELECT password_hash FROM users WHERE id = ?";
        $user = $this->db->fetchRow($sql, [$this->id]);
        
        if (!password_verify($currentPassword, $user['password_hash'])) {
            throw new Exception("Current password is incorrect");
        }
        
        // Update password
        $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password_hash = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        
        $this->db->execute($sql, [$newPasswordHash, $this->id]);
        
        return true;
    }
    
    /**
     * Verify email address
     */
    public function verifyEmail() {
        if (!$this->id) {
            throw new Exception("User not loaded");
        }
        
        $sql = "UPDATE users SET email_verified = TRUE, 
                updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        
        $this->db->execute($sql, [$this->id]);
        
        return true;
    }
    
    /**
     * Check if email exists
     */
    private function emailExists($email) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE email = ?";
        $result = $this->db->fetchRow($sql, [$email]);
        return $result['count'] > 0;
    }
    
    /**
     * Update last login timestamp
     */
    private function updateLastLogin($userId) {
        $sql = "UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?";
        $this->db->execute($sql, [$userId]);
    }
    
    /**
     * Populate object properties from array
     */
    private function populateFromArray($data) {
        $this->id = $data['id'];
        $this->uuid = $data['uuid'];
        $this->email = $data['email'];
        $this->firstName = $data['first_name'];
        $this->lastName = $data['last_name'];
        $this->userType = $data['user_type'];
        $this->status = $data['status'];
        $this->createdAt = $data['created_at'] ?? null;
        $this->lastLogin = $data['last_login'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->profileImage = $data['profile_image'] ?? null;
    }
    
    /**
     * Get all users with pagination
     */
    public function getAll($page = 1, $limit = 20, $filters = []) {
        $offset = ($page - 1) * $limit;
        
        $whereClause = "WHERE 1=1";
        $params = [];
        
        if (!empty($filters['user_type'])) {
            $whereClause .= " AND user_type = ?";
            $params[] = $filters['user_type'];
        }
        
        if (!empty($filters['status'])) {
            $whereClause .= " AND status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['search'])) {
            $whereClause .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql = "SELECT id, uuid, email, first_name, last_name, user_type, status, 
                created_at, last_login FROM users {$whereClause} 
                ORDER BY created_at DESC LIMIT ? OFFSET ?";
        
        $params[] = $limit;
        $params[] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get total count for pagination
     */
    public function getTotalCount($filters = []) {
        $whereClause = "WHERE 1=1";
        $params = [];
        
        if (!empty($filters['user_type'])) {
            $whereClause .= " AND user_type = ?";
            $params[] = $filters['user_type'];
        }
        
        if (!empty($filters['status'])) {
            $whereClause .= " AND status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['search'])) {
            $whereClause .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql = "SELECT COUNT(*) as total FROM users {$whereClause}";
        
        $result = $this->db->fetchRow($sql, $params);
        return $result['total'];
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getUuid() { return $this->uuid; }
    public function getEmail() { return $this->email; }
    public function getFirstName() { return $this->firstName; }
    public function getLastName() { return $this->lastName; }
    public function getFullName() { return $this->firstName . ' ' . $this->lastName; }
    public function getUserType() { return $this->userType; }
    public function getStatus() { return $this->status; }
    public function getCreatedAt() { return $this->createdAt ?? null; }
    public function getLastLogin() { return $this->lastLogin ?? null; }
    public function getPhone() { return $this->phone ?? null; }
    public function getProfileImage() { return $this->profileImage ?? null; }
    
    /**
     * Check if user has specific type
     */
    public function isAdmin() { return $this->userType === 'admin'; }
    public function isCompany() { return $this->userType === 'company'; }
    public function isValidator() { return $this->userType === 'validator'; }
    public function isConsumer() { return $this->userType === 'consumer'; }
    
    /**
     * Check if user is active
     */
    public function isActive() { return $this->status === 'active'; }
    
    /**
     * Get user preferences
     */
    public function getPreferences() {
        if ($this->preferences && method_exists($this->preferences, 'getPreferences')) {
            return $this->preferences->getPreferences($this->id);
        }
        return [];
    }
    
    /**
     * Get a specific user preference
     */
    public function getPreference($key, $default = null) {
        if ($this->preferences && method_exists($this->preferences, 'getPreference')) {
            return $this->preferences->getPreference($this->id, $key, $default);
        }
        return $default;
    }
    
    /**
     * Set a user preference
     */
    public function setPreference($key, $value) {
        if ($this->preferences && method_exists($this->preferences, 'setPreference')) {
            return $this->preferences->setPreference($this->id, $key, $value);
        }
        return false;
    }
    
    /**
     * Set multiple user preferences
     */
    public function setPreferences($preferences) {
        if ($this->preferences && method_exists($this->preferences, 'setPreferences')) {
            return $this->preferences->setPreferences($this->id, $preferences);
        }
        return false;
    }
    
    /**
     * Get 2FA status
     */
    public function get2FAStatus() {
        if ($this->twoFactorAuth && method_exists($this->twoFactorAuth, 'get2FAStatus')) {
            return $this->twoFactorAuth->get2FAStatus($this->id);
        }
        return false;
    }
    
    /**
     * Enable 2FA
     */
    public function enable2FA($secret) {
        if ($this->twoFactorAuth && method_exists($this->twoFactorAuth, 'enable2FA')) {
            return $this->twoFactorAuth->enable2FA($this->id, $secret);
        }
        return false;
    }
    
    /**
     * Disable 2FA
     */
    public function disable2FA() {
        if ($this->twoFactorAuth && method_exists($this->twoFactorAuth, 'disable2FA')) {
            return $this->twoFactorAuth->disable2FA($this->id);
        }
        return false;
    }
    
    /**
     * Verify 2FA code
     */
    public function verify2FACode($code) {
        if ($this->twoFactorAuth && method_exists($this->twoFactorAuth, 'verifyCode')) {
            return $this->twoFactorAuth->verifyCode($this->id, $code);
        }
        return false;
    }
    
    /**
     * Get 2FA backup codes
     */
    public function get2FABackupCodes() {
        if ($this->twoFactorAuth && method_exists($this->twoFactorAuth, 'getBackupCodes')) {
            return $this->twoFactorAuth->getBackupCodes($this->id);
        }
        return [];
    }
    
    /**
     * Get user devices
     */
    public function getDevices() {
        if ($this->devices && method_exists($this->devices, 'getUserDevices')) {
            return $this->devices->getUserDevices($this->id);
        }
        return [];
    }
    
    /**
     * Register current device
     */
    public function registerCurrentDevice($sessionId) {
        if ($this->devices && method_exists($this->devices, 'registerDevice')) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            // Check if the method exists before calling it
            if (method_exists($this->devices, 'getDeviceInfoFromUserAgent')) {
                $deviceInfo = $this->devices->getDeviceInfoFromUserAgent($userAgent);
            } else {
                $deviceInfo = [
                    'user_agent' => $userAgent,
                    'browser' => 'Unknown',
                    'os' => 'Unknown',
                    'device' => 'Unknown'
                ];
            }
            $deviceInfo['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
            
            return $this->devices->registerDevice($this->id, $sessionId, $deviceInfo);
        }
        return false;
    }
    
    /**
     * Convert to array for JSON response
     */
    public function toArray($includePrivate = false) {
        $data = [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'email' => $this->email,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'full_name' => $this->getFullName(),
            'user_type' => $this->userType,
            'status' => $this->status
        ];
        
        return $data;
    }
}
?>