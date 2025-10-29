<?php
/**
 * Add Sample Validation Data Script
 * 
 * This script adds sample validation records to the database for testing the validator dashboard.
 */

// Define required constants
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');
define('DATABASE_DIR', ROOT_DIR . '/database');

// Include required files
require_once CONFIG_DIR . '/database.php';
require_once CLASSES_DIR . '/Database.php';
require_once CLASSES_DIR . '/User.php';

try {
    // Get database instance
    $db = Database::getInstance();
    
    echo "Adding sample validation data...\n";
    
    // Check if we have a test validator user
    $sql = "SELECT id FROM users WHERE email = 'test@validator.com' AND user_type = 'validator'";
    $validatorUser = $db->fetchRow($sql);
    
    if (!$validatorUser) {
        echo "Test validator user not found. Please run the database installation first.\n";
        exit(1);
    }
    
    // Get the validator record for this user
    $sql = "SELECT id FROM validators WHERE user_id = ?";
    $validator = $db->fetchRow($sql, [$validatorUser['id']]);
    
    if (!$validator) {
        echo "Validator record not found for test user. Creating one...\n";
        
        // Create a validator record
        $uuid = $db->generateUUID();
        $sql = "INSERT INTO validators (uuid, user_id, validator_name, organization_type, status) VALUES (?, ?, ?, ?, ?)";
        $db->insert($sql, [$uuid, $validatorUser['id'], 'Test Validator', 'independent', 'active']);
        $validatorId = $db->getLastInsertId();
    } else {
        $validatorId = $validator['id'];
    }
    
    echo "Using validator ID: $validatorId\n";
    
    // Check if we have any products and supply chain steps
    $sql = "SELECT id FROM supply_chain_steps LIMIT 5";
    $steps = $db->fetchAll($sql);
    
    if (empty($steps)) {
        echo "No supply chain steps found. Please add sample products and supply chain data first.\n";
        exit(1);
    }
    
    // Add sample validation records with different statuses
    $sampleValidations = [
        [
            'supply_chain_step_id' => $steps[0]['id'],
            'validation_type' => 'document',
            'validation_result' => 'approved',
            'findings' => 'All documents verified and compliant with standards.',
            'status' => 'completed',
            'requested_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            'completed_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
            'response_time_hours' => 24
        ],
        [
            'supply_chain_step_id' => $steps[1]['id'] ?? $steps[0]['id'],
            'validation_type' => 'field_visit',
            'validation_result' => 'approved',
            'findings' => 'Facility inspection completed. All safety protocols followed.',
            'status' => 'completed',
            'requested_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            'completed_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            'response_time_hours' => 18
        ],
        [
            'supply_chain_step_id' => $steps[2]['id'] ?? $steps[0]['id'],
            'validation_type' => 'third_party_audit',
            'validation_result' => 'rejected',
            'findings' => 'Non-compliance with labor standards found. Immediate corrective actions required.',
            'status' => 'completed',
            'requested_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            'completed_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
            'response_time_hours' => 36
        ],
        [
            'supply_chain_step_id' => $steps[3]['id'] ?? $steps[0]['id'],
            'validation_type' => 'document',
            'validation_result' => 'approved',
            'findings' => 'Documentation review complete. All certificates valid.',
            'status' => 'completed',
            'requested_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            'completed_at' => date('Y-m-d H:i:s'),
            'response_time_hours' => 12
        ],
        [
            'supply_chain_step_id' => $steps[4]['id'] ?? $steps[0]['id'],
            'validation_type' => 'field_visit',
            'validation_result' => 'needs_clarification',
            'findings' => 'Additional information required regarding waste management procedures.',
            'status' => 'completed',
            'requested_at' => date('Y-m-d H:i:s', strtotime('-6 hours')),
            'completed_at' => date('Y-m-d H:i:s'),
            'response_time_hours' => 6
        ],
        [
            'supply_chain_step_id' => $steps[0]['id'],
            'validation_type' => 'document',
            'validation_result' => 'pending',
            'findings' => null,
            'status' => 'pending',
            'requested_at' => date('Y-m-d H:i:s')
        ],
        [
            'supply_chain_step_id' => $steps[1]['id'] ?? $steps[0]['id'],
            'validation_type' => 'field_visit',
            'validation_result' => 'pending',
            'findings' => null,
            'status' => 'pending',
            'requested_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))
        ],
        [
            'supply_chain_step_id' => $steps[2]['id'] ?? $steps[0]['id'],
            'validation_type' => 'third_party_audit',
            'validation_result' => 'pending',
            'findings' => null,
            'status' => 'pending',
            'requested_at' => date('Y-m-d H:i:s', strtotime('-30 minutes'))
        ]
    ];
    
    $addedCount = 0;
    foreach ($sampleValidations as $validation) {
        // Generate UUID for validation
        $uuid = $db->generateUUID();
        
        // Check if this validation already exists
        $checkSql = "SELECT id FROM validation_records WHERE uuid = ?";
        $existing = $db->fetchRow($checkSql, [$uuid]);
        
        if (!$existing) {
            $sql = "INSERT INTO validation_records (
                uuid, supply_chain_step_id, validator_id, validation_type, 
                validation_result, findings, status, requested_at, completed_at, response_time_hours
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $params = [
                $uuid,
                $validation['supply_chain_step_id'],
                $validatorId,
                $validation['validation_type'],
                $validation['validation_result'],
                $validation['findings'],
                $validation['status'],
                $validation['requested_at'],
                $validation['completed_at'] ?? null,
                $validation['response_time_hours'] ?? null
            ];
            
            $db->insert($sql, $params);
            $addedCount++;
        }
    }
    
    echo "Added $addedCount sample validation records.\n";
    
    // Update validator statistics
    $sql = "UPDATE validators SET 
            total_validations = (SELECT COUNT(*) FROM validation_records WHERE validator_id = ?),
            successful_validations = (SELECT COUNT(*) FROM validation_records WHERE validator_id = ? AND validation_result = 'approved'),
            average_response_time = (SELECT AVG(response_time_hours) FROM validation_records WHERE validator_id = ? AND response_time_hours IS NOT NULL)
            WHERE id = ?";
    
    $db->execute($sql, [$validatorId, $validatorId, $validatorId, $validatorId]);
    
    echo "Updated validator statistics.\n";
    echo "Sample validation data added successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>