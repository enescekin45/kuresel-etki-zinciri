<?php
/**
 * Add Sample Validation Records
 * 
 * Creates sample validation records for testing the pending validations page
 */

define('ROOT_DIR', dirname(__DIR__));
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

require_once CLASSES_DIR . '/Database.php';

try {
    $db = Database::getInstance();
    
    // Check if we have validators
    $validators = $db->fetchAll("SELECT id, user_id, validator_name FROM validators LIMIT 5");
    
    if (empty($validators)) {
        echo "❌ No validators found in the database. Please create at least one validator first.\n";
        exit(1);
    }
    
    echo "✅ Found " . count($validators) . " validators:\n";
    foreach ($validators as $validator) {
        echo "  - ID: {$validator['id']}, Name: {$validator['validator_name']}\n";
    }
    
    // Get the first validator for our sample data
    $validatorId = $validators[0]['id'];
    
    // Check if we have products
    $products = $db->fetchAll("SELECT id, product_name FROM products LIMIT 5");
    
    if (empty($products)) {
        echo "❌ No products found in the database. Please create at least one product first.\n";
        exit(1);
    }
    
    echo "✅ Found " . count($products) . " products:\n";
    foreach ($products as $product) {
        echo "  - ID: {$product['id']}, Name: {$product['product_name']}\n";
    }
    
    // Get the first product for our sample data
    $productId = $products[0]['id'];
    
    // Check if we have product batches
    $batches = $db->fetchAll("SELECT id, batch_number FROM product_batches WHERE product_id = ? LIMIT 5", [$productId]);
    
    if (empty($batches)) {
        echo "❌ No batches found for product ID {$productId}. Creating a sample batch...\n";
        
        // Create a sample batch
        $batchSql = "INSERT INTO product_batches (uuid, product_id, batch_number, production_date, expiry_date, quantity, unit) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
        $batchUuid = uniqid('batch_', true);
        $batchNumber = 'BATCH-' . date('Y') . '-' . rand(1000, 9999);
        $batchId = $db->insert($batchSql, [
            $batchUuid,
            $productId,
            $batchNumber,
            date('Y-m-d'),
            date('Y-m-d', strtotime('+1 year')),
            1000,
            'pieces'
        ]);
        
        echo "✅ Created batch ID: {$batchId}, Number: {$batchNumber}\n";
    } else {
        echo "✅ Found " . count($batches) . " batches for product ID {$productId}\n";
        $batchId = $batches[0]['id'];
    }
    
    // Check if we have companies
    $companies = $db->fetchAll("SELECT id, company_name FROM companies LIMIT 5");
    
    if (empty($companies)) {
        echo "❌ No companies found in the database. Please create at least one company first.\n";
        exit(1);
    }
    
    echo "✅ Found " . count($companies) . " companies:\n";
    foreach ($companies as $company) {
        echo "  - ID: {$company['id']}, Name: {$company['company_name']}\n";
    }
    
    // Get the first company for our sample data
    $companyId = $companies[0]['id'];
    
    // Check if we have supply chain steps
    $steps = $db->fetchAll("SELECT id, step_name FROM supply_chain_steps WHERE product_batch_id = ? LIMIT 5", [$batchId]);
    
    if (empty($steps)) {
        echo "❌ No supply chain steps found for batch ID {$batchId}. Creating sample steps...\n";
        
        // Create sample supply chain steps
        $stepsToCreate = [
            ['raw_material', 'Ham Madde Tedariki', 'Organik pamuk temini'],
            ['processing', 'İşleme', 'Pamuk işleme ve temizleme'],
            ['manufacturing', 'Üretim', 'Kumaş üretimi'],
            ['packaging', 'Ambalajlama', 'Çevre dostu ambalaj'],
            ['logistics', 'Lojistik', 'Depoya nakliye']
        ];
        
        $stepIds = [];
        foreach ($stepsToCreate as $index => $stepData) {
            $stepSql = "INSERT INTO supply_chain_steps (uuid, product_batch_id, company_id, step_type, step_name, step_description, step_order, validation_status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stepUuid = uniqid('step_', true);
            $stepId = $db->insert($stepSql, [
                $stepUuid,
                $batchId,
                $companyId,
                $stepData[0],
                $stepData[1],
                $stepData[2],
                $index + 1,
                'pending'
            ]);
            
            $stepIds[] = $stepId;
            echo "✅ Created step ID: {$stepId}, Name: {$stepData[1]}\n";
        }
    } else {
        echo "✅ Found " . count($steps) . " supply chain steps for batch ID {$batchId}\n";
        $stepIds = array_column($steps, 'id');
    }
    
    // Create sample validation records
    echo "Creating sample validation records...\n";
    
    $validationTypes = ['document', 'field_visit', 'third_party_audit', 'blockchain_verification'];
    $sampleValidations = [
        [
            'supply_chain_step_id' => $stepIds[0],
            'validation_type' => $validationTypes[array_rand($validationTypes)],
            'validation_method' => 'Document Review',
            'validation_criteria' => json_encode(['organic_cert_required' => true, 'pesticide_test_required' => true])
        ],
        [
            'supply_chain_step_id' => $stepIds[1],
            'validation_type' => $validationTypes[array_rand($validationTypes)],
            'validation_method' => 'On-site Inspection',
            'validation_criteria' => json_encode(['processing_standards' => 'ISO 22000', 'worker_safety_protocols' => true])
        ],
        [
            'supply_chain_step_id' => $stepIds[2],
            'validation_type' => $validationTypes[array_rand($validationTypes)],
            'validation_method' => 'Quality Testing',
            'validation_criteria' => json_encode(['material_composition' => '100% organic cotton', 'color_fastness' => 'Grade A'])
        ]
    ];
    
    $createdCount = 0;
    foreach ($sampleValidations as $validationData) {
        // Check if validation record already exists
        $existingSql = "SELECT id FROM validation_records WHERE supply_chain_step_id = ? AND validator_id = ?";
        $existing = $db->fetchRow($existingSql, [$validationData['supply_chain_step_id'], $validatorId]);
        
        if (!$existing) {
            // Create validation record
            $validationSql = "INSERT INTO validation_records (uuid, supply_chain_step_id, validator_id, validation_type, validation_method, validation_criteria, validation_result, status, requested_at) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $validationUuid = uniqid('validation_', true);
            
            $validationId = $db->insert($validationSql, [
                $validationUuid,
                $validationData['supply_chain_step_id'],
                $validatorId,
                $validationData['validation_type'],
                $validationData['validation_method'],
                $validationData['validation_criteria'],
                'pending', // Keep as pending for the pending validations page
                'pending',
                date('Y-m-d H:i:s', strtotime('-' . rand(1, 7) . ' days')) // Random date in the last week
            ]);
            
            echo "✅ Created validation record ID: {$validationId} for step ID: {$validationData['supply_chain_step_id']}\n";
            $createdCount++;
        } else {
            echo "ℹ️ Validation record already exists for step ID: {$validationData['supply_chain_step_id']}\n";
        }
    }
    
    echo "✅ Successfully created {$createdCount} sample validation records!\n";
    echo "You should now see pending validations on the page: http://localhost/Küresel/?page=validation&action=pending\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>