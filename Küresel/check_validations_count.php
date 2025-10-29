<?php
/**
 * Check validation records count
 */

define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

require_once CLASSES_DIR . '/Database.php';

try {
    $db = Database::getInstance();
    
    // Check total validation records
    $sql = "SELECT COUNT(*) as total FROM validation_records";
    $result = $db->fetchRow($sql);
    echo "Total validation records: " . $result['total'] . "\n";
    
    // Check validation records by status
    $sql = "SELECT status, COUNT(*) as count FROM validation_records GROUP BY status";
    $results = $db->fetchAll($sql);
    echo "Validation records by status:\n";
    foreach ($results as $row) {
        echo "  {$row['status']}: {$row['count']}\n";
    }
    
    // Check validation records by validator
    $sql = "SELECT validator_id, COUNT(*) as count FROM validation_records GROUP BY validator_id";
    $results = $db->fetchAll($sql);
    echo "Validation records by validator:\n";
    foreach ($results as $row) {
        echo "  Validator ID {$row['validator_id']}: {$row['count']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>