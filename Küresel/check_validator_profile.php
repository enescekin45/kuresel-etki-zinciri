<?php
/**
 * Check if current user has a validator profile
 */

define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

require_once CLASSES_DIR . '/Database.php';
require_once CLASSES_DIR . '/Auth.php';
require_once CLASSES_DIR . '/Validator.php';

try {
    session_start();
    
    $auth = Auth::getInstance();
    
    if (!$auth->isLoggedIn()) {
        echo "User is not logged in.\n";
        exit(1);
    }
    
    $currentUser = $auth->getCurrentUser();
    
    echo "Current User:\n";
    echo "  ID: " . $currentUser->getId() . "\n";
    echo "  Name: " . $currentUser->getFullName() . "\n";
    echo "  Email: " . $currentUser->getEmail() . "\n";
    echo "  Type: " . $currentUser->getUserType() . "\n";
    echo "  Is Validator: " . ($currentUser->isValidator() ? 'Yes' : 'No') . "\n\n";
    
    if (!$currentUser->isValidator()) {
        echo "User is not a validator.\n";
        exit(1);
    }
    
    // Try to load validator profile
    $validator = new Validator();
    try {
        $validator->loadByUserId($currentUser->getId());
        echo "Validator Profile Found:\n";
        echo "  ID: " . $validator->getId() . "\n";
        echo "  Name: " . $validator->getValidatorName() . "\n";
        echo "  Status: " . $validator->getStatus() . "\n";
        echo "  Reputation Score: " . $validator->getReputationScore() . "\n\n";
        
        // Get statistics
        $stats = $validator->getStatistics();
        echo "Validator Statistics:\n";
        print_r($stats);
        
    } catch (Exception $e) {
        echo "Error loading validator profile: " . $e->getMessage() . "\n";
        
        // Check if there's a validator record for this user
        $db = Database::getInstance();
        $sql = "SELECT * FROM validators WHERE user_id = ?";
        $validatorRecord = $db->fetchRow($sql, [$currentUser->getId()]);
        
        if ($validatorRecord) {
            echo "Validator record exists in database:\n";
            print_r($validatorRecord);
        } else {
            echo "No validator record found for user ID: " . $currentUser->getId() . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>