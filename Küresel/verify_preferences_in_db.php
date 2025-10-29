<?php
require_once 'config/bootstrap.php';

try {
    $auth = Auth::getInstance();
    
    if (!$auth->isLoggedIn()) {
        echo "User not logged in\n";
        exit;
    }
    
    $user = $auth->getCurrentUser();
    $userId = $user->getId();
    
    echo "Checking preferences for user ID: $userId\n";
    
    // Direct database query to check preferences
    $db = Database::getInstance();
    $sql = "SELECT preference_key, preference_value FROM user_preferences WHERE user_id = ?";
    $preferences = $db->fetchAll($sql, [$userId]);
    
    echo "Current preferences in database:\n";
    foreach ($preferences as $pref) {
        echo "  {$pref['preference_key']}: {$pref['preference_value']}\n";
    }
    
    if (empty($preferences)) {
        echo "No preferences found for this user.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>