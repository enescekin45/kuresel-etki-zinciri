<?php
// Define required constants
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

require_once CONFIG_DIR . '/database.php';
require_once CLASSES_DIR . '/Database.php';
require_once CLASSES_DIR . '/User.php';

try {
    $db = Database::getInstance();
    $user = new User();
    $user->loadByEmail('test@validator.com');
    echo 'User ID: ' . $user->getId() . PHP_EOL;
    
    $result = $db->fetchAll('SELECT * FROM validators WHERE user_id = ?', [$user->getId()]);
    if (!empty($result)) {
        echo 'Validator record found:' . PHP_EOL;
        print_r($result[0]);
    } else {
        echo 'No validator record found for user ID: ' . $user->getId() . PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
?>