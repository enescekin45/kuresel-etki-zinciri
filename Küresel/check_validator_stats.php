<?php
/**
 * Check validator statistics directly
 */

define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

require_once CLASSES_DIR . '/Database.php';
require_once CLASSES_DIR . '/Validator.php';

try {
    // Load validator with ID 2
    $validator = new Validator();
    $validator->loadById(2);
    
    // Get statistics
    $stats = $validator->getStatistics();
    
    echo "Validator Statistics for ID 2:\n";
    print_r($stats);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>