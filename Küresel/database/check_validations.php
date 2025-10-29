<?php
/**
 * Check Validation Records
 * 
 * Verifies that validation records exist in the database
 */

define('ROOT_DIR', dirname(__DIR__));
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

require_once CLASSES_DIR . '/Database.php';

try {
    $db = Database::getInstance();
    
    // Check validation records
    $sql = "SELECT vr.id, vr.status, vr.requested_at, vr.validator_id, 
                   scs.step_name, p.product_name, c.company_name 
            FROM validation_records vr
            JOIN supply_chain_steps scs ON vr.supply_chain_step_id = scs.id
            JOIN product_batches pb ON scs.product_batch_id = pb.id
            JOIN products p ON pb.product_id = p.id
            JOIN companies c ON scs.company_id = c.id
            ORDER BY vr.requested_at DESC
            LIMIT 10";
    
    $records = $db->fetchAll($sql);
    
    echo "Total validation records found: " . count($records) . "\n\n";
    
    if (empty($records)) {
        echo "No validation records found in the database.\n";
        exit(1);
    }
    
    echo "Validation Records:\n";
    echo str_repeat("-", 80) . "\n";
    foreach ($records as $record) {
        echo "ID: {$record['id']}\n";
        echo "Status: {$record['status']}\n";
        echo "Requested: {$record['requested_at']}\n";
        echo "Validator ID: {$record['validator_id']}\n";
        echo "Step: {$record['step_name']}\n";
        echo "Product: {$record['product_name']}\n";
        echo "Company: {$record['company_name']}\n";
        echo str_repeat("-", 40) . "\n";
    }
    
    // Check pending validations specifically
    $pendingSql = "SELECT COUNT(*) as count FROM validation_records WHERE status = 'pending'";
    $pendingResult = $db->fetchRow($pendingSql);
    
    echo "Pending validation records: {$pendingResult['count']}\n\n";
    
    // Check if there are validators
    $validatorSql = "SELECT id, validator_name FROM validators LIMIT 5";
    $validators = $db->fetchAll($validatorSql);
    
    echo "Available validators:\n";
    foreach ($validators as $validator) {
        echo "  - ID: {$validator['id']}, Name: {$validator['validator_name']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>