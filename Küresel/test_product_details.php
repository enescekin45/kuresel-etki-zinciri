<?php
// Test product details API to verify all information is returned correctly

// Define ROOT_DIR constant
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

// Include required files
require_once ROOT_DIR . '/config/database.php';
require_once ROOT_DIR . '/classes/Database.php';
require_once ROOT_DIR . '/classes/Product.php';

try {
    $db = Database::getInstance();
    
    // Get a product to test
    $sql = "SELECT uuid, product_name, product_code FROM products WHERE product_code = 'PRD-GIDA-001'";
    $product = $db->fetchRow($sql);
    
    if (!$product) {
        throw new Exception("Test product not found");
    }
    
    echo "Testing product details for: " . $product['product_name'] . " (" . $product['product_code'] . ")\n";
    echo "Product UUID: " . $product['uuid'] . "\n\n";
    
    // Load product using the Product class
    $productObj = new Product();
    $productObj->loadByUuid($product['uuid']);
    
    // Get detailed product data
    $productData = $productObj->toArray(true);
    
    echo "=== PRODUCT DETAILS ===\n";
    echo "Name: " . $productData['product_name'] . "\n";
    echo "Code: " . $productData['product_code'] . "\n";
    echo "Barcode: " . ($productData['barcode'] ?? 'Not specified') . "\n";
    echo "Category: " . $productData['category'] . "\n";
    echo "Subcategory: " . ($productData['subcategory'] ?? 'Not specified') . "\n";
    echo "Brand: " . ($productData['brand'] ?? 'Not specified') . "\n";
    echo "Description: " . ($productData['description'] ?? 'Not specified') . "\n";
    echo "Weight: " . ($productData['weight'] ?? 'Not specified') . " kg\n";
    echo "Volume: " . ($productData['volume'] ?? 'Not specified') . " L\n";
    echo "Packaging: " . ($productData['packaging_type'] ?? 'Not specified') . "\n";
    echo "Shelf Life: " . ($productData['shelf_life'] ?? 'Not specified') . " days\n";
    echo "Origin: " . ($productData['origin_country'] ?? 'Not specified') . " - " . ($productData['origin_region'] ?? 'Not specified') . "\n";
    echo "Harvest Season: " . ($productData['harvest_season'] ?? 'Not specified') . "\n";
    
    // Check product images
    echo "\n=== PRODUCT IMAGES ===\n";
    if (isset($productData['product_images']) && is_array($productData['product_images']) && count($productData['product_images']) > 0) {
        foreach ($productData['product_images'] as $image) {
            echo "- " . $image . "\n";
        }
    } else {
        echo "No images found\n";
    }
    
    // Get batches
    $batches = $productObj->getBatches();
    echo "\n=== BATCHES ===\n";
    if (count($batches) > 0) {
        foreach ($batches as $batch) {
            echo "Batch Number: " . $batch['batch_number'] . "\n";
            echo "Production Date: " . $batch['production_date'] . "\n";
            echo "Expiry Date: " . $batch['expiry_date'] . "\n";
            echo "Quantity: " . $batch['quantity'] . " " . $batch['unit'] . "\n";
            echo "Quality Grade: " . $batch['quality_grade'] . "\n\n";
        }
    } else {
        echo "No batches found\n";
    }
    
    // Get supply chain steps for the first batch
    echo "=== SUPPLY CHAIN STEPS ===\n";
    if (count($batches) > 0) {
        $supplyChainSteps = $productObj->getSupplyChainSteps($batches[0]['id']);
        if (count($supplyChainSteps) > 0) {
            foreach ($supplyChainSteps as $step) {
                echo "Step " . $step['step_order'] . ": " . $step['step_name'] . "\n";
                echo "  Type: " . $step['step_type'] . "\n";
                echo "  Description: " . $step['step_description'] . "\n";
                echo "  Location: " . $step['address'] . "\n";
                echo "  Duration: " . $step['duration_hours'] . " hours\n";
                echo "  Carbon Emissions: " . $step['carbon_emissions'] . " kg CO2\n";
                echo "  Water Usage: " . $step['water_usage'] . " liters\n";
                echo "  Energy Consumption: " . $step['energy_consumption'] . " kWh\n";
                echo "  Worker Count: " . $step['worker_count'] . "\n";
                echo "  Worker Satisfaction: " . $step['worker_satisfaction_score'] . "/5\n";
                echo "  Validation Status: " . $step['validation_status'] . " (Score: " . $step['validation_score'] . "/5)\n\n";
            }
        } else {
            echo "No supply chain steps found\n";
        }
    } else {
        echo "No batches available to get supply chain steps\n";
    }
    
    // Get impact scores for the first batch
    echo "=== IMPACT SCORES ===\n";
    if (count($batches) > 0) {
        $impactScores = $productObj->getImpactScores($batches[0]['id']);
        if (count($impactScores) > 0) {
            $scores = $impactScores[0]; // Get the most recent
            echo "Overall Score: " . $scores['overall_score'] . "/10 (Grade: " . $scores['overall_grade'] . ")\n";
            echo "Environmental Score: " . $scores['environmental_score'] . "/10\n";
            echo "  - Carbon Footprint: " . $scores['carbon_footprint_score'] . "/10\n";
            echo "  - Water Footprint: " . $scores['water_footprint_score'] . "/10\n";
            echo "  - Biodiversity Impact: " . $scores['biodiversity_impact_score'] . "/10\n";
            echo "Social Score: " . $scores['social_score'] . "/10\n";
            echo "  - Fair Wages: " . $scores['fair_wages_score'] . "/10\n";
            echo "  - Working Conditions: " . $scores['working_conditions_score'] . "/10\n";
            echo "  - Community Impact: " . $scores['community_impact_score'] . "/10\n";
            echo "Transparency Score: " . $scores['transparency_score'] . "/10\n";
            echo "Equivalent Environmental Impact:\n";
            echo "  - " . $scores['equivalent_car_km'] . " km car travel\n";
            echo "  - " . $scores['equivalent_shower_minutes'] . " minutes of shower\n";
            echo "  - " . $scores['equivalent_home_energy_days'] . " days of home energy use\n\n";
        } else {
            echo "No impact scores found\n";
        }
    } else {
        echo "No batches available to get impact scores\n";
    }
    
    // Get certificates from documentation
    echo "=== CERTIFICATES ===\n";
    if (isset($productData['documentation']) && !empty($productData['documentation'])) {
        $certificates = json_decode($productData['documentation'], true);
        if (is_array($certificates) && count($certificates) > 0) {
            foreach ($certificates as $cert) {
                echo "Certificate: " . $cert['name'] . "\n";
                echo "  Issuer: " . $cert['issuer'] . "\n";
                echo "  Validity: " . $cert['validity'] . "\n";
                echo "  Number: " . $cert['certificate_number'] . "\n";
                echo "  Description: " . $cert['description'] . "\n\n";
            }
        } else {
            echo "No certificates found or could not decode\n";
        }
    } else {
        echo "No certificates data found\n";
    }
    
    echo "Product details test completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>