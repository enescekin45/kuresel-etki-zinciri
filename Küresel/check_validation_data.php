<?php
// Define required constants
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

require_once CONFIG_DIR . '/database.php';
require_once CLASSES_DIR . '/Database.php';

try {
    $db = Database::getInstance();
    $result = $db->fetchAll('SELECT COUNT(*) as count FROM validation_records');
    echo 'Total validation records: ' . $result[0]['count'] . PHP_EOL;
    
    // Check validator statistics
    $result = $db->fetchAll('SELECT id, total_validations, successful_validations, average_response_time FROM validators');
    foreach ($result as $validator) {
        echo 'Validator ID: ' . $validator['id'] . PHP_EOL;
        echo '  Total validations: ' . $validator['total_validations'] . PHP_EOL;
        echo '  Successful validations: ' . $validator['successful_validations'] . PHP_EOL;
        echo '  Average response time: ' . $validator['average_response_time'] . ' hours' . PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
?>