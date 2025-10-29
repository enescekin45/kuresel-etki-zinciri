<?php
/**
 * Check if there's a validator profile for user ID 22
 */

define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

require_once CLASSES_DIR . '/Database.php';

try {
    $db = Database::getInstance();
    
    // Check if there's a validator profile for user ID 22
    $sql = "SELECT * FROM validators WHERE user_id = 22";
    $validator = $db->fetchRow($sql);
    
    if ($validator) {
        echo "Validator profile found for user ID 22:\n";
        print_r($validator);
    } else {
        echo "No validator profile found for user ID 22.\n";
        
        // Check all validators
        $sql = "SELECT * FROM validators";
        $validators = $db->fetchAll($sql);
        echo "All validators in database:\n";
        foreach ($validators as $v) {
            echo "  ID: {$v['id']}, User ID: {$v['user_id']}, Name: {$v['validator_name']}\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>