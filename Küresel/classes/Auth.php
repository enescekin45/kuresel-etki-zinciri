<?php
/**
 * Authentication Class
 * 
 * Handles user authentication, session management, and security
 */

require_once CLASSES_DIR . '/Database.php';

class Auth {
    private $db;
    private $user;
    private static $instance = null;
    
    private function __construct() {
        $this->db = Database::getInstance();
        $this->initializeSession();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Auth();
        }
        return self::$instance;
    }
    
    /**
     * Initialize session if not already started
     */
    private function initializeSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Regenerate session ID periodically for security
        if (!isset($_SESSION['last_regeneration'])) {
            $this->regenerateSession();
        } elseif (time() - $_SESSION['last_regeneration'] > 1800) { // 30 minutes
            $this->regenerateSession();
        }
    }
    
    /**
     * Login user with email and password
     */
    public function login($email, $password, $rememberMe = false) {
        try {
            // Rate limiting check
            if ($this->isRateLimited($email)) {
                throw new Exception("Too many login attempts. Please try again later.");
            }
            
            $user = new User();
            
            if (!$user->authenticate($email, $password)) {
                $this->logFailedAttempt($email);
                throw new Exception("Invalid email or password");
            }
            
            // Clear failed attempts on successful login
            $this->clearFailedAttempts($email);
            
            // Set session data
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_uuid'] = $user->getUuid();
            $_SESSION['user_type'] = $user->getUserType();
            $_SESSION['user_email'] = $user->getEmail();
            $_SESSION['user_name'] = $user->getFullName();
            $_SESSION['logged_in'] = true;
            $_SESSION['login_time'] = time();
            
            // Handle remember me
            if ($rememberMe) {
                $this->setRememberMeToken($user->getId());
            }
            
            $this->user = $user;
            
            // Register device
            $user->registerCurrentDevice(session_id());
            
            // Log successful login
            $this->logAuthEvent('login_success', $user->getId());
            
            return true;
            
        } catch (Exception $e) {
            $this->logAuthEvent('login_failed', null, ['email' => $email, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * Register new user
     */
    public function register($userData) {
        try {
            // Additional validation
            if (!$this->isValidEmail($userData['email'])) {
                throw new Exception("Invalid email format");
            }
            
            if (!$this->isValidPassword($userData['password'])) {
                throw new Exception("Password must be at least 8 characters long and contain letters and numbers");
            }
            
            // Create user
            $user = new User();
            $userId = $user->create($userData);
            
            // Send verification email (placeholder)
            $this->sendVerificationEmail($userData['email'], $user->getUuid());
            
            // Log registration
            $this->logAuthEvent('user_registered', $userId);
            
            return $userId;
            
        } catch (Exception $e) {
            $this->logAuthEvent('registration_failed', null, ['email' => $userData['email'] ?? '', 'error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * Logout current user
     */
    public function logout() {
        $userId = $this->getCurrentUserId();
        
        // Clear remember me token
        if (isset($_COOKIE['remember_token'])) {
            $this->clearRememberMeToken();
        }
        
        // Log logout
        if ($userId) {
            $this->logAuthEvent('logout', $userId);
        }
        
        // Clear session
        $_SESSION = [];
        
        // Destroy session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        
        $this->user = null;
    }
    
    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            // Check remember me token
            return $this->checkRememberMeToken();
        }
        
        // Check session timeout (24 hours)
        if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > 86400) {
            $this->logout();
            return false;
        }
        
        return true;
    }
    
    /**
     * Get current user ID
     */
    public function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get current user UUID
     */
    public function getCurrentUserUuid() {
        return $_SESSION['user_uuid'] ?? null;
    }
    
    /**
     * Get current user type
     */
    public function getCurrentUserType() {
        return $_SESSION['user_type'] ?? null;
    }
    
    /**
     * Get current user object
     */
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        if ($this->user === null) {
            $this->user = new User();
            $this->user->loadById($this->getCurrentUserId());
        }
        
        return $this->user;
    }
    
    /**
     * Check if current user has specific role
     */
    public function hasRole($role) {
        $userType = $this->getCurrentUserType();
        return $userType === $role;
    }
    
    /**
     * Check if current user is admin
     */
    public function isAdmin() {
        return $this->hasRole('admin');
    }
    
    /**
     * Check if current user is company
     */
    public function isCompany() {
        return $this->hasRole('company');
    }
    
    /**
     * Check if current user is validator
     */
    public function isValidator() {
        return $this->hasRole('validator');
    }
    
    /**
     * Check if current user is consumer
     */
    public function isConsumer() {
        return $this->hasRole('consumer');
    }
    
    /**
     * Require login (redirect if not logged in)
     */
    public function requireLogin($redirectUrl = '/') {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin($redirectUrl);
        }
    }
    
    /**
     * Require specific role
     */
    public function requireRole($role, $redirectUrl = '/') {
        $this->requireLogin($redirectUrl);
        
        if (!$this->hasRole($role)) {
            http_response_code(403);
            throw new Exception("Access denied. Required role: {$role}");
        }
    }
    
    /**
     * Change password for current user
     */
    public function changePassword($currentPassword, $newPassword) {
        if (!$this->isLoggedIn()) {
            throw new Exception("User not logged in");
        }
        
        if (!$this->isValidPassword($newPassword)) {
            throw new Exception("New password must be at least 8 characters long and contain letters and numbers");
        }
        
        $user = $this->getCurrentUser();
        $success = $user->changePassword($currentPassword, $newPassword);
        
        if ($success) {
            $this->logAuthEvent('password_changed', $this->getCurrentUserId());
        }
        
        return $success;
    }
    
    /**
     * Request password reset
     */
    public function requestPasswordReset($email) {
        // Check if user exists
        try {
            $user = new User();
            $user->loadByEmail($email);
            
            // Generate reset token
            $token = $this->generateResetToken($user->getId());
            
            // Send reset email (placeholder)
            $this->sendPasswordResetEmail($email, $token);
            
            $this->logAuthEvent('password_reset_requested', $user->getId());
            
            return true;
            
        } catch (Exception $e) {
            // Don't reveal if email exists
            return true;
        }
    }
    
    /**
     * Regenerate session ID
     */
    private function regenerateSession() {
        // Check if headers have already been sent
        if (!headers_sent()) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        } else {
            // Log the issue but don't try to regenerate if headers are already sent
            error_log("Warning: Could not regenerate session ID - headers already sent");
        }
    }
    
    /**
     * Check rate limiting
     */
    private function isRateLimited($email) {
        $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = 'login_attempts_' . md5($email . $remoteAddr);
        
        // Simple file-based rate limiting (could be enhanced with Redis)
        $attemptsFile = sys_get_temp_dir() . '/' . $key;
        
        if (file_exists($attemptsFile)) {
            $attempts = json_decode(file_get_contents($attemptsFile), true);
            
            // Remove old attempts (older than 15 minutes)
            $attempts = array_filter($attempts, function($time) {
                return (time() - $time) < 900;
            });
            
            // Check if too many attempts
            if (count($attempts) >= 5) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Log failed login attempt
     */
    private function logFailedAttempt($email) {
        $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = 'login_attempts_' . md5($email . $remoteAddr);
        $attemptsFile = sys_get_temp_dir() . '/' . $key;
        
        $attempts = [];
        if (file_exists($attemptsFile)) {
            $attempts = json_decode(file_get_contents($attemptsFile), true) ?: [];
        }
        
        $attempts[] = time();
        file_put_contents($attemptsFile, json_encode($attempts));
    }
    
    /**
     * Clear failed login attempts
     */
    private function clearFailedAttempts($email) {
        $key = 'login_attempts_' . md5($email . $_SERVER['REMOTE_ADDR']);
        $attemptsFile = sys_get_temp_dir() . '/' . $key;
        
        if (file_exists($attemptsFile)) {
            unlink($attemptsFile);
        }
    }
    
    /**
     * Set remember me token
     */
    private function setRememberMeToken($userId) {
        $token = bin2hex(random_bytes(32));
        $hashedToken = password_hash($token, PASSWORD_BCRYPT);
        $expiry = time() + (30 * 24 * 60 * 60); // 30 days
        
        // Store in database (you might want to create a separate table for this)
        $sql = "INSERT INTO user_tokens (user_id, token_hash, type, expires_at) VALUES (?, ?, 'remember_me', FROM_UNIXTIME(?))
                ON DUPLICATE KEY UPDATE token_hash = VALUES(token_hash), expires_at = VALUES(expires_at)";
        
        try {
            $this->db->execute($sql, [$userId, $hashedToken, $expiry]);
            
            // Set cookie
            setcookie('remember_token', $token, $expiry, '/', '', true, true);
        } catch (Exception $e) {
            // If token table doesn't exist, just skip remember me functionality
            error_log("Remember me token storage failed: " . $e->getMessage());
        }
    }
    
    /**
     * Check remember me token
     */
    private function checkRememberMeToken() {
        if (!isset($_COOKIE['remember_token'])) {
            return false;
        }
        
        $token = $_COOKIE['remember_token'];
        
        try {
            $sql = "SELECT user_id, token_hash FROM user_tokens 
                    WHERE type = 'remember_me' AND expires_at > NOW()";
            $tokens = $this->db->fetchAll($sql);
            
            foreach ($tokens as $tokenData) {
                if (password_verify($token, $tokenData['token_hash'])) {
                    // Valid token found, log user in
                    $user = new User();
                    $user->loadById($tokenData['user_id']);
                    
                    $_SESSION['user_id'] = $user->getId();
                    $_SESSION['user_uuid'] = $user->getUuid();
                    $_SESSION['user_type'] = $user->getUserType();
                    $_SESSION['user_email'] = $user->getEmail();
                    $_SESSION['user_name'] = $user->getFullName();
                    $_SESSION['logged_in'] = true;
                    $_SESSION['login_time'] = time();
                    
                    $this->user = $user;
                    
                    return true;
                }
            }
        } catch (Exception $e) {
            // Token table might not exist
            error_log("Remember me token check failed: " . $e->getMessage());
        }
        
        // Invalid token, clear cookie
        $this->clearRememberMeToken();
        return false;
    }
    
    /**
     * Clear remember me token
     */
    private function clearRememberMeToken() {
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }
        
        try {
            $sql = "DELETE FROM user_tokens WHERE user_id = ? AND type = 'remember_me'";
            $this->db->execute($sql, [$this->getCurrentUserId()]);
        } catch (Exception $e) {
            // Token table might not exist
        }
    }
    
    /**
     * Generate password reset token
     */
    private function generateResetToken($userId) {
        $token = bin2hex(random_bytes(32));
        $hashedToken = password_hash($token, PASSWORD_BCRYPT);
        $expiry = time() + (60 * 60); // 1 hour
        
        try {
            $sql = "INSERT INTO user_tokens (user_id, token_hash, type, expires_at) VALUES (?, ?, 'password_reset', FROM_UNIXTIME(?))
                    ON DUPLICATE KEY UPDATE token_hash = VALUES(token_hash), expires_at = VALUES(expires_at)";
            $this->db->execute($sql, [$userId, $hashedToken, $expiry]);
        } catch (Exception $e) {
            error_log("Password reset token storage failed: " . $e->getMessage());
        }
        
        return $token;
    }
    
    /**
     * Validate email format
     */
    private function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate password strength
     */
    private function isValidPassword($password) {
        // At least 8 characters, contains letters and numbers
        return strlen($password) >= 8 && 
               preg_match('/[A-Za-z]/', $password) && 
               preg_match('/[0-9]/', $password);
    }
    
    /**
     * Send verification email (placeholder)
     */
    private function sendVerificationEmail($email, $uuid) {
        // Implement email sending logic
        // For now, just log it
        error_log("Verification email should be sent to: {$email} with UUID: {$uuid}");
    }
    
    /**
     * Send password reset email (placeholder)
     */
    private function sendPasswordResetEmail($email, $token) {
        // Implement email sending logic
        // For now, just log it
        error_log("Password reset email should be sent to: {$email} with token: {$token}");
    }
    
    /**
     * Log authentication events
     */
    private function logAuthEvent($action, $userId = null, $data = []) {
        try {
            $sql = "INSERT INTO audit_logs (uuid, user_id, action, table_name, 
                    ip_address, user_agent, new_values) 
                    VALUES (?, ?, ?, 'auth', ?, ?, ?)";
            
            $uuid = $this->db->generateUUID();
            $params = [
                $uuid,
                $userId,
                $action,
                $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                json_encode($data)
            ];
            
            $this->db->execute($sql, $params);
        } catch (Exception $e) {
            error_log("Failed to log auth event: " . $e->getMessage());
        }
    }
    
    /**
     * Redirect to login page
     */
    private function redirectToLogin($returnUrl = '/') {
        $loginUrl = '/login';
        if ($returnUrl !== '/') {
            $loginUrl .= '?return=' . urlencode($returnUrl);
        }
        
        header("Location: {$loginUrl}");
        exit;
    }
    
    /**
     * Generate CSRF token
     */
    public function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Verify CSRF token
     */
    public function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Get user's session info
     */
    public function getSessionInfo() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'user_id' => $this->getCurrentUserId(),
            'user_uuid' => $this->getCurrentUserUuid(),
            'user_type' => $this->getCurrentUserType(),
            'user_email' => $_SESSION['user_email'] ?? '',
            'user_name' => $_SESSION['user_name'] ?? '',
            'login_time' => $_SESSION['login_time'] ?? 0,
            'session_duration' => time() - ($_SESSION['login_time'] ?? time())
        ];
    }
}
?>