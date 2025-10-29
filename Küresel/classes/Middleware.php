<?php
/**
 * Middleware Class
 * 
 * Handles authentication and authorization middleware for API endpoints
 */

class Middleware {
    
    /**
     * API middleware - handles authentication for API requests
     */
    public function apiMiddleware() {
        // For API requests, we'll handle authentication in each endpoint
        // This is just a placeholder for now
        header('Content-Type: application/json');
    }
    
    /**
     * Require authentication for API endpoints
     */
    public function authenticate() {
        $auth = Auth::getInstance();
        
        if (!$auth->isLoggedIn()) {
            throw new Exception('Authentication required', 401);
        }
    }
    
    /**
     * Require specific role
     */
    public function requireRole($role) {
        $auth = Auth::getInstance();
        
        if (!$auth->isLoggedIn()) {
            throw new Exception('Authentication required', 401);
        }
        
        if (!$auth->hasRole($role)) {
            throw new Exception('Insufficient permissions', 403);
        }
    }
}
?>