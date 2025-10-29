<?php
require_once 'config/bootstrap.php';

echo "<h1>Consumer Panel Access Test</h1>";

// Check if we can load the Auth class
try {
    $auth = Auth::getInstance();
    echo "<p>✅ Auth class loaded successfully</p>";
    
    // Check if user is logged in
    if ($auth->isLoggedIn()) {
        echo "<p>✅ User is logged in</p>";
        
        // Get current user
        $currentUser = $auth->getCurrentUser();
        if ($currentUser) {
            echo "<p>✅ Current user loaded: " . $currentUser->getEmail() . "</p>";
            echo "<p>✅ User type: " . $currentUser->getUserType() . "</p>";
            
            // Check if user is consumer
            if ($currentUser->isConsumer()) {
                echo "<p>✅ User is a consumer</p>";
                echo "<p>✅ User should be able to access consumer panel</p>";
            } else {
                echo "<p>❌ User is not a consumer (type: " . $currentUser->getUserType() . ")</p>";
            }
        } else {
            echo "<p>❌ Failed to load current user</p>";
        }
    } else {
        echo "<p>❌ User is not logged in</p>";
        echo "<p><a href='/Küresel/?page=login'>Login here</a></p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}

// Check if consumer.php file exists
if (file_exists('frontend/pages/consumer.php')) {
    echo "<p>✅ consumer.php file exists</p>";
    
    // Check file size
    $filesize = filesize('frontend/pages/consumer.php');
    echo "<p>✅ consumer.php file size: " . $filesize . " bytes</p>";
} else {
    echo "<p>❌ consumer.php file does not exist</p>";
}

echo "<p><a href='/Küresel/?page=consumer'>Try to access consumer panel</a></p>";
?>