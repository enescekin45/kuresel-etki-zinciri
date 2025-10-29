<?php
/**
 * Check users in database
 */

define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

require_once CLASSES_DIR . '/Database.php';

try {
    $db = Database::getInstance();
    
    // Check validator users
    $sql = "SELECT id, email, first_name, last_name, user_type FROM users WHERE user_type = 'validator' LIMIT 5";
    $users = $db->fetchAll($sql);
    
    echo "Validator Users:\n";
    if (empty($users)) {
        echo "  No validator users found.\n";
    } else {
        foreach ($users as $user) {
            echo "  ID: {$user['id']}, Name: {$user['first_name']} {$user['last_name']}, Email: {$user['email']}\n";
        }
    }
    
    // Check all users
    $sql = "SELECT id, email, first_name, last_name, user_type FROM users LIMIT 10";
    $users = $db->fetchAll($sql);
    
    echo "\nAll Users:\n";
    foreach ($users as $user) {
        echo "  ID: {$user['id']}, Name: {$user['first_name']} {$user['last_name']}, Email: {$user['email']}, Type: {$user['user_type']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>