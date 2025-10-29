<?php
// Simple test to check if consumer panel works
echo "<h1>Simple Consumer Panel Test</h1>";

// Check if we can access the session
session_start();
echo "<p>Session ID: " . session_id() . "</p>";

// Check if we can load the Auth class
if (file_exists('classes/Auth.php')) {
    require_once 'classes/Auth.php';
    echo "<p>✅ Auth class file found</p>";
    
    try {
        $auth = Auth::getInstance();
        echo "<p>✅ Auth instance created</p>";
        
        if ($auth->isLoggedIn()) {
            echo "<p>✅ User is logged in</p>";
            
            $user = $auth->getCurrentUser();
            if ($user) {
                echo "<p>✅ User object loaded</p>";
                echo "<p>User email: " . $user->getEmail() . "</p>";
                echo "<p>User type: " . $user->getUserType() . "</p>";
                
                if ($user->isConsumer()) {
                    echo "<p>✅ User is a consumer</p>";
                    echo "<p>Access granted to consumer panel</p>";
                } else {
                    echo "<p>❌ User is not a consumer</p>";
                }
            } else {
                echo "<p>❌ Failed to load user object</p>";
            }
        } else {
            echo "<p>❌ User is not logged in</p>";
            echo "<p><a href='/Küresel/?page=login'>Login</a></p>";
        }
    } catch (Exception $e) {
        echo "<p>❌ Error creating Auth instance: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>❌ Auth class file not found</p>";
}
?>