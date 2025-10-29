<?php
/**
 * Check User Validator
 * 
 * Checks which validator profile is associated with the current user
 */

define('ROOT_DIR', dirname(__DIR__));
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

require_once CLASSES_DIR . '/Database.php';
require_once CLASSES_DIR . '/Auth.php';
require_once CLASSES_DIR . '/Validator.php';

try {
    // Start session to access current user
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
        echo "User is not a validator. Only validators can view pending validations.\n";
        exit(1);
    }
    
    // Try to load validator profile
    $validator = new Validator();
    try {
        $validator->loadByUserId($currentUser->getId());
        echo "Validator Profile Found:\n";
        echo "  ID: " . $validator->getId() . "\n";
        echo "  Name: " . $validator->getValidatorName() . "\n";
        echo "  Status: " . $validator->getStatus() . "\n\n";
        
        // Get pending validations for this validator
        $pendingValidations = $validator->getPendingValidations(1, 20);
        echo "Pending validations for this validator: " . count($pendingValidations) . "\n";
        
        if (!empty($pendingValidations)) {
            echo "\nPending Validation Details:\n";
            foreach ($pendingValidations as $validation) {
                echo "  - ID: {$validation['id']}, Product: {$validation['product_name']}, Company: {$validation['company_name']}\n";
            }
        } else {
            echo "\nNo pending validations found for this validator.\n";
            
            // Check if there are pending validations for other validators
            $db = Database::getInstance();
            $sql = "SELECT vr.id, vr.validator_id, scs.step_name, p.product_name, c.company_name 
                    FROM validation_records vr
                    JOIN supply_chain_steps scs ON vr.supply_chain_step_id = scs.id
                    JOIN product_batches pb ON scs.product_batch_id = pb.id
                    JOIN products p ON pb.product_id = p.id
                    JOIN companies c ON scs.company_id = c.id
                    WHERE vr.status = 'pending'
                    ORDER BY vr.requested_at DESC
                    LIMIT 5";
            
            $allPending = $db->fetchAll($sql);
            if (!empty($allPending)) {
                echo "\nHowever, there are pending validations in the database assigned to other validators:\n";
                foreach ($allPending as $validation) {
                    echo "  - ID: {$validation['id']}, Validator ID: {$validation['validator_id']}, Product: {$validation['product_name']}\n";
                }
            }
        }
        
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