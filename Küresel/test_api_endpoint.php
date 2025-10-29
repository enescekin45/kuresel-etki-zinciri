<?php
// Test the API endpoint directly to verify all information is returned correctly

// Define ROOT_DIR constant
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');
define('API_DIR', ROOT_DIR . '/api');

// Include required files
require_once ROOT_DIR . '/config/database.php';
require_once ROOT_DIR . '/classes/Database.php';
require_once ROOT_DIR . '/classes/Product.php';

echo "Testing API endpoint for product details...\n";

// Get a product UUID to test
$db = Database::getInstance();
$sql = "SELECT uuid FROM products WHERE product_code = 'PRD-GIDA-001'";
$product = $db->fetchRow($sql);

if (!$product) {
    echo "Test product not found\n";
    exit(1);
}

$testUuid = $product['uuid'];
echo "Testing with product UUID: " . $testUuid . "\n\n";

// Simulate API request
$_GET['uuid'] = $testUuid;

// Capture output
ob_start();

// Include the API endpoint
include API_DIR . '/v1/products/get.php';

$responseText = ob_get_clean();

echo "Raw API Response:\n";
echo $responseText . "\n\n";

// Try to parse JSON
$json = json_decode($responseText, true);
if ($json) {
    echo "Parsed JSON successfully\n";
    echo "Success: " . ($json['success'] ? 'true' : 'false') . "\n";
    echo "Message: " . $json['message'] . "\n";
    
    if ($json['success'] && isset($json['data'])) {
        $data = $json['data'];
        echo "\n=== PRODUCT INFORMATION ===\n";
        echo "Name: " . $data['product_name'] . "\n";
        echo "Code: " . $data['product_code'] . "\n";
        echo "Brand: " . ($data['brand'] ?? 'Not specified') . "\n";
        echo "Category: " . $data['category'] . "\n";
        
        // Check if supply chain steps are included
        if (isset($data['supply_chain_steps']) && is_array($data['supply_chain_steps'])) {
            echo "\nSupply Chain Steps: " . count($data['supply_chain_steps']) . " steps found\n";
            foreach ($data['supply_chain_steps'] as $step) {
                echo "  - " . $step['step_name'] . " (" . $step['step_type'] . ")\n";
            }
        } else {
            echo "\nSupply Chain Steps: Not found\n";
        }
        
        // Check if impact scores are included
        if (isset($data['impact_scores'])) {
            echo "\nImpact Scores: Found\n";
            echo "  Overall Score: " . $data['impact_scores']['overall_score'] . "/10\n";
            echo "  Environmental Score: " . $data['impact_scores']['environmental_score'] . "/10\n";
            echo "  Social Score: " . $data['impact_scores']['social_score'] . "/10\n";
        } else {
            echo "\nImpact Scores: Not found\n";
        }
        
        // Check if certificates are included
        if (isset($data['certificates']) && is_array($data['certificates'])) {
            echo "\nCertificates: " . count($data['certificates']) . " certificates found\n";
            foreach ($data['certificates'] as $cert) {
                echo "  - " . $cert['name'] . " (" . $cert['issuer'] . ")\n";
            }
        } else {
            echo "\nCertificates: Not found or not properly parsed\n";
        }
        
        // Check if images are included
        if (isset($data['product_images']) && is_array($data['product_images'])) {
            echo "\nProduct Images: " . count($data['product_images']) . " images found\n";
        } else {
            echo "\nProduct Images: Not found\n";
        }
    }
} else {
    echo "Failed to parse JSON response\n";
}

echo "\nAPI endpoint test completed!\n";
?>