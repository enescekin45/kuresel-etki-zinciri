<?php
// Add impact scores for all product batches

// Define ROOT_DIR constant only if not already defined
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', __DIR__);
}
if (!defined('CONFIG_DIR')) {
    define('CONFIG_DIR', ROOT_DIR . '/config');
}
if (!defined('CLASSES_DIR')) {
    define('CLASSES_DIR', ROOT_DIR . '/classes');
}

// Include required files
require_once ROOT_DIR . '/config/database.php';
require_once ROOT_DIR . '/classes/Database.php';

try {
    $db = Database::getInstance();
    
    // Get all product batches
    $sql = "SELECT pb.id as batch_id, p.product_name, p.product_code 
            FROM product_batches pb 
            JOIN products p ON pb.product_id = p.id 
            ORDER BY pb.id";
    $batches = $db->fetchAll($sql);
    
    echo "Adding impact scores for " . count($batches) . " batches...\n";
    
    foreach ($batches as $batchData) {
        $batchId = $batchData['batch_id'];
        $productName = $batchData['product_name'];
        $productCode = $batchData['product_code'];
        
        echo "Adding impact scores for: " . $productName . " (Batch ID: " . $batchId . ")\n";
        
        // Delete existing impact scores for this batch (if any)
        $deleteSql = "DELETE FROM impact_scores WHERE product_batch_id = ?";
        $db->execute($deleteSql, [$batchId]);
        
        // Generate impact scores based on product category
        $impactScores = [];
        
        if (strpos($productCode, 'GIDA') !== false) {
            // Food product impact scores
            $impactScores = [
                'overall_score' => 8.2,
                'overall_grade' => 'B',
                'environmental_score' => 7.8,
                'carbon_footprint_score' => 7.5,
                'water_footprint_score' => 8.0,
                'biodiversity_impact_score' => 8.2,
                'waste_score' => 8.5,
                'social_score' => 8.5,
                'fair_wages_score' => 8.8,
                'working_conditions_score' => 8.2,
                'community_impact_score' => 8.7,
                'labor_rights_score' => 8.3,
                'transparency_score' => 8.3,
                'data_completeness_score' => 9.0,
                'third_party_validation_score' => 8.5,
                'update_frequency_score' => 8.0,
                'source_credibility_score' => 8.2,
                'total_carbon_footprint' => 52.3,
                'total_water_footprint' => 1250.0,
                'total_energy_consumption' => 171.1,
                'total_waste_generated' => 19.5,
                'equivalent_car_km' => 215.0,
                'equivalent_shower_minutes' => 75.0,
                'equivalent_home_energy_days' => 2.8,
                'data_completeness_percentage' => 92.0,
                'confidence_level' => 88.5
            ];
        } elseif (strpos($productCode, 'TEKSTIL') !== false) {
            // Textile product impact scores
            $impactScores = [
                'overall_score' => 7.5,
                'overall_grade' => 'C',
                'environmental_score' => 7.2,
                'carbon_footprint_score' => 6.8,
                'water_footprint_score' => 7.5,
                'biodiversity_impact_score' => 7.0,
                'waste_score' => 7.8,
                'social_score' => 7.8,
                'fair_wages_score' => 8.0,
                'working_conditions_score' => 7.5,
                'community_impact_score' => 8.2,
                'labor_rights_score' => 7.5,
                'transparency_score' => 7.5,
                'data_completeness_score' => 8.5,
                'third_party_validation_score' => 8.0,
                'update_frequency_score' => 7.0,
                'source_credibility_score' => 7.8,
                'total_carbon_footprint' => 87.5,
                'total_water_footprint' => 2850.0,
                'total_energy_consumption' => 353.4,
                'total_waste_generated' => 72.9,
                'equivalent_car_km' => 360.0,
                'equivalent_shower_minutes' => 170.0,
                'equivalent_home_energy_days' => 4.8,
                'data_completeness_percentage' => 87.0,
                'confidence_level' => 82.0
            ];
        } elseif (strpos($productCode, 'ELEK') !== false) {
            // Electronic product impact scores
            $impactScores = [
                'overall_score' => 6.8,
                'overall_grade' => 'D',
                'environmental_score' => 6.2,
                'carbon_footprint_score' => 5.5,
                'water_footprint_score' => 7.0,
                'biodiversity_impact_score' => 6.5,
                'waste_score' => 6.8,
                'social_score' => 7.5,
                'fair_wages_score' => 7.8,
                'working_conditions_score' => 7.2,
                'community_impact_score' => 7.8,
                'labor_rights_score' => 7.0,
                'transparency_score' => 7.0,
                'data_completeness_score' => 8.0,
                'third_party_validation_score' => 7.5,
                'update_frequency_score' => 6.5,
                'source_credibility_score' => 7.2,
                'total_carbon_footprint' => 285.3,
                'total_water_footprint' => 1520.0,
                'total_energy_consumption' => 789.6,
                'total_waste_generated' => 125.8,
                'equivalent_car_km' => 1170.0,
                'equivalent_shower_minutes' => 90.0,
                'equivalent_home_energy_days' => 10.8,
                'data_completeness_percentage' => 82.0,
                'confidence_level' => 78.0
            ];
        } elseif (strpos($productCode, 'KOZ') !== false) {
            // Cosmetic product impact scores
            $impactScores = [
                'overall_score' => 9.0,
                'overall_grade' => 'A',
                'environmental_score' => 9.2,
                'carbon_footprint_score' => 8.8,
                'water_footprint_score' => 9.5,
                'biodiversity_impact_score' => 9.0,
                'waste_score' => 9.5,
                'social_score' => 8.8,
                'fair_wages_score' => 9.0,
                'working_conditions_score' => 8.5,
                'community_impact_score' => 9.2,
                'labor_rights_score' => 8.8,
                'transparency_score' => 9.0,
                'data_completeness_score' => 9.5,
                'third_party_validation_score' => 9.2,
                'update_frequency_score' => 8.8,
                'source_credibility_score' => 9.0,
                'total_carbon_footprint' => 28.7,
                'total_water_footprint' => 850.0,
                'total_energy_consumption' => 108.9,
                'total_waste_generated' => 15.3,
                'equivalent_car_km' => 118.0,
                'equivalent_shower_minutes' => 50.0,
                'equivalent_home_energy_days' => 1.5,
                'data_completeness_percentage' => 95.0,
                'confidence_level' => 92.0
            ];
        }
        
        // Insert impact scores
        $scoreSql = "INSERT INTO impact_scores (
            uuid, product_batch_id, overall_score, overall_grade, environmental_score,
            carbon_footprint_score, water_footprint_score, biodiversity_impact_score,
            waste_score, social_score, fair_wages_score, working_conditions_score,
            community_impact_score, labor_rights_score, transparency_score,
            data_completeness_score, third_party_validation_score, update_frequency_score,
            source_credibility_score, total_carbon_footprint, total_water_footprint,
            total_energy_consumption, total_waste_generated, equivalent_car_km,
            equivalent_shower_minutes, equivalent_home_energy_days,
            data_completeness_percentage, confidence_level
        ) VALUES (
            UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )";
        
        $db->execute($scoreSql, [
            $batchId,
            $impactScores['overall_score'],
            $impactScores['overall_grade'],
            $impactScores['environmental_score'],
            $impactScores['carbon_footprint_score'],
            $impactScores['water_footprint_score'],
            $impactScores['biodiversity_impact_score'],
            $impactScores['waste_score'],
            $impactScores['social_score'],
            $impactScores['fair_wages_score'],
            $impactScores['working_conditions_score'],
            $impactScores['community_impact_score'],
            $impactScores['labor_rights_score'],
            $impactScores['transparency_score'],
            $impactScores['data_completeness_score'],
            $impactScores['third_party_validation_score'],
            $impactScores['update_frequency_score'],
            $impactScores['source_credibility_score'],
            $impactScores['total_carbon_footprint'],
            $impactScores['total_water_footprint'],
            $impactScores['total_energy_consumption'],
            $impactScores['total_waste_generated'],
            $impactScores['equivalent_car_km'],
            $impactScores['equivalent_shower_minutes'],
            $impactScores['equivalent_home_energy_days'],
            $impactScores['data_completeness_percentage'],
            $impactScores['confidence_level']
        ]);
        
        echo "Added impact scores for: " . $productName . "\n";
    }
    
    echo "Impact scores addition completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>