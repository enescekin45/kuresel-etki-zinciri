<?php
// Add supply chain steps for all products

// Define ROOT_DIR constant
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

// Include required files
require_once ROOT_DIR . '/config/database.php';
require_once ROOT_DIR . '/classes/Database.php';

try {
    $db = Database::getInstance();
    
    // Get all products with their batches
    $sql = "SELECT p.id as product_id, p.product_name, p.product_code, pb.id as batch_id 
            FROM products p 
            JOIN product_batches pb ON p.id = pb.product_id 
            ORDER BY p.id";
    $products = $db->fetchAll($sql);
    
    echo "Adding supply chain data for " . count($products) . " product batches...\n";
    
    foreach ($products as $productData) {
        $productId = $productData['product_id'];
        $productName = $productData['product_name'];
        $productCode = $productData['product_code'];
        $batchId = $productData['batch_id'];
        
        echo "Adding supply chain for: " . $productName . " (Batch ID: " . $batchId . ")\n";
        
        // Get company ID for this product
        $companySql = "SELECT company_id FROM products WHERE id = ?";
        $companyResult = $db->fetchRow($companySql, [$productId]);
        $companyId = $companyResult['company_id'];
        
        // Delete existing supply chain steps for this batch (if any)
        $deleteSql = "DELETE FROM supply_chain_steps WHERE product_batch_id = ?";
        $db->execute($deleteSql, [$batchId]);
        
        // Add supply chain steps based on product category
        $supplyChainSteps = [];
        
        if (strpos($productCode, 'GIDA') !== false) {
            // Food product supply chain
            $supplyChainSteps = [
                [
                    'step_type' => 'raw_material',
                    'step_name' => 'Ham Madde Temini',
                    'step_description' => 'Organik tarım uygulamalarıyla üretilmiş ham maddelerin toplanması',
                    'step_order' => 1,
                    'location_coordinates' => json_encode(['lat' => 38.4237, 'lng' => 27.1428]), // Izmir coordinates
                    'address' => 'Organik Tarım Alanı, Ege Bölgesi, Türkiye',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-45 days')),
                    'end_date' => date('Y-m-d H:i:s', strtotime('-40 days')),
                    'duration_hours' => 120,
                    'carbon_emissions' => 5.2,
                    'water_usage' => 150.0,
                    'energy_consumption' => 25.5,
                    'waste_generated' => 2.1,
                    'renewable_energy_percentage' => 35.0,
                    'worker_count' => 15,
                    'average_wage' => 25.50,
                    'working_hours_per_day' => 8.0,
                    'safety_incidents' => 0,
                    'worker_satisfaction_score' => 4.2,
                    'certificates' => json_encode(['Organic Certification', 'Fair Trade']),
                    'validation_status' => 'validated',
                    'validation_score' => 4.8
                ],
                [
                    'step_type' => 'processing',
                    'step_name' => 'İşleme ve Üretim',
                    'step_description' => 'Ham maddelerin işlenerek son ürüne dönüştürülmesi',
                    'step_order' => 2,
                    'location_coordinates' => json_encode(['lat' => 38.3529, 'lng' => 27.2341]), // Manisa coordinates
                    'address' => 'Üretim Tesisi, Manisa, Türkiye',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-35 days')),
                    'end_date' => date('Y-m-d H:i:s', strtotime('-25 days')),
                    'duration_hours' => 240,
                    'carbon_emissions' => 12.8,
                    'water_usage' => 320.0,
                    'energy_consumption' => 85.2,
                    'waste_generated' => 8.5,
                    'renewable_energy_percentage' => 45.0,
                    'worker_count' => 25,
                    'average_wage' => 28.75,
                    'working_hours_per_day' => 8.0,
                    'safety_incidents' => 1,
                    'worker_satisfaction_score' => 4.0,
                    'certificates' => json_encode(['ISO 22000', 'HACCP']),
                    'validation_status' => 'validated',
                    'validation_score' => 4.6
                ],
                [
                    'step_type' => 'packaging',
                    'step_name' => 'Ambalajlama',
                    'step_description' => 'Ürünlerin çevre dostu ambalaj malzemeleriyle paketlenmesi',
                    'step_order' => 3,
                    'location_coordinates' => json_encode(['lat' => 41.0082, 'lng' => 28.9784]), // Istanbul coordinates
                    'address' => 'Ambalaj Tesisi, Istanbul, Türkiye',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-20 days')),
                    'end_date' => date('Y-m-d H:i:s', strtotime('-15 days')),
                    'duration_hours' => 120,
                    'carbon_emissions' => 3.5,
                    'water_usage' => 45.0,
                    'energy_consumption' => 32.1,
                    'waste_generated' => 5.2,
                    'renewable_energy_percentage' => 60.0,
                    'worker_count' => 12,
                    'average_wage' => 26.30,
                    'working_hours_per_day' => 8.0,
                    'safety_incidents' => 0,
                    'worker_satisfaction_score' => 4.5,
                    'certificates' => json_encode(['FSC', 'Recycled Packaging']),
                    'validation_status' => 'validated',
                    'validation_score' => 4.9
                ],
                [
                    'step_type' => 'logistics',
                    'step_name' => 'Dağıtım',
                    'step_description' => 'Ürünlerin perakende noktalarına dağıtımı',
                    'step_order' => 4,
                    'location_coordinates' => json_encode(['lat' => 39.9334, 'lng' => 32.8597]), // Ankara coordinates
                    'address' => 'Dağıtım Merkezi, Ankara, Türkiye',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-10 days')),
                    'end_date' => date('Y-m-d H:i:s', strtotime('-5 days')),
                    'duration_hours' => 120,
                    'carbon_emissions' => 25.6,
                    'water_usage' => 0.0,
                    'energy_consumption' => 45.8,
                    'waste_generated' => 1.2,
                    'renewable_energy_percentage' => 25.0,
                    'worker_count' => 8,
                    'average_wage' => 24.50,
                    'working_hours_per_day' => 8.0,
                    'safety_incidents' => 0,
                    'worker_satisfaction_score' => 4.1,
                    'transport_mode' => 'truck',
                    'distance_km' => 450.0,
                    'fuel_type' => 'diesel',
                    'fuel_consumption' => 35.2,
                    'certificates' => json_encode(['Green Logistics']),
                    'validation_status' => 'validated',
                    'validation_score' => 4.3
                ]
            ];
        } elseif (strpos($productCode, 'TEKSTIL') !== false) {
            // Textile product supply chain
            $supplyChainSteps = [
                [
                    'step_type' => 'raw_material',
                    'step_name' => 'Pamuk Tarımı',
                    'step_description' => 'Organik pamuk tarımı ve hasadı',
                    'step_order' => 1,
                    'location_coordinates' => json_encode(['lat' => 38.4237, 'lng' => 27.1428]), // Izmir coordinates
                    'address' => 'Organik Pamuk Çiftliği, Ege Bölgesi, Türkiye',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-60 days')),
                    'end_date' => date('Y-m-d H:i:s', strtotime('-50 days')),
                    'duration_hours' => 240,
                    'carbon_emissions' => 8.5,
                    'water_usage' => 500.0,
                    'energy_consumption' => 35.2,
                    'waste_generated' => 3.2,
                    'renewable_energy_percentage' => 30.0,
                    'worker_count' => 20,
                    'average_wage' => 22.50,
                    'working_hours_per_day' => 8.0,
                    'safety_incidents' => 0,
                    'worker_satisfaction_score' => 4.0,
                    'certificates' => json_encode(['Organic Cotton', 'Fair Trade']),
                    'validation_status' => 'validated',
                    'validation_score' => 4.7
                ],
                [
                    'step_type' => 'processing',
                    'step_name' => 'İplik Üretimi',
                    'step_description' => 'Pamuk liflerinin ipliğe dönüştürülmesi',
                    'step_order' => 2,
                    'location_coordinates' => json_encode(['lat' => 41.0082, 'lng' => 28.9784]), // Istanbul coordinates
                    'address' => 'İplik Fabrikası, Istanbul, Türkiye',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-45 days')),
                    'end_date' => date('Y-m-d H:i:s', strtotime('-35 days')),
                    'duration_hours' => 240,
                    'carbon_emissions' => 15.2,
                    'water_usage' => 280.0,
                    'energy_consumption' => 95.6,
                    'waste_generated' => 12.5,
                    'renewable_energy_percentage' => 40.0,
                    'worker_count' => 30,
                    'average_wage' => 26.75,
                    'working_hours_per_day' => 8.0,
                    'safety_incidents' => 1,
                    'worker_satisfaction_score' => 3.8,
                    'certificates' => json_encode(['ISO 14001', 'Oeko-Tex']),
                    'validation_status' => 'validated',
                    'validation_score' => 4.5
                ],
                [
                    'step_type' => 'manufacturing',
                    'step_name' => 'Dokuma ve Dikim',
                    'step_description' => 'Kumaşın dokunması ve giysinin dikilmesi',
                    'step_order' => 3,
                    'location_coordinates' => json_encode(['lat' => 40.7128, 'lng' => 29.9255]), // Izmit coordinates
                    'address' => 'Dikim Atölyesi, Kocaeli, Türkiye',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-30 days')),
                    'end_date' => date('Y-m-d H:i:s', strtotime('-20 days')),
                    'duration_hours' => 240,
                    'carbon_emissions' => 12.8,
                    'water_usage' => 150.0,
                    'energy_consumption' => 78.3,
                    'waste_generated' => 8.7,
                    'renewable_energy_percentage' => 50.0,
                    'worker_count' => 25,
                    'average_wage' => 24.30,
                    'working_hours_per_day' => 8.0,
                    'safety_incidents' => 0,
                    'worker_satisfaction_score' => 4.2,
                    'certificates' => json_encode(['WRAP', 'SA8000']),
                    'validation_status' => 'validated',
                    'validation_score' => 4.6
                ],
                [
                    'step_type' => 'packaging',
                    'step_name' => 'Ambalajlama',
                    'step_description' => 'Ürünlerin geri dönüştürülmüş ambalaj malzemeleriyle paketlenmesi',
                    'step_order' => 4,
                    'location_coordinates' => json_encode(['lat' => 41.0082, 'lng' => 28.9784]), // Istanbul coordinates
                    'address' => 'Ambalaj Tesisi, Istanbul, Türkiye',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-15 days')),
                    'end_date' => date('Y-m-d H:i:s', strtotime('-10 days')),
                    'duration_hours' => 120,
                    'carbon_emissions' => 2.5,
                    'water_usage' => 25.0,
                    'energy_consumption' => 18.7,
                    'waste_generated' => 3.1,
                    'renewable_energy_percentage' => 70.0,
                    'worker_count' => 10,
                    'average_wage' => 23.50,
                    'working_hours_per_day' => 8.0,
                    'safety_incidents' => 0,
                    'worker_satisfaction_score' => 4.4,
                    'certificates' => json_encode(['FSC', 'Recycled Packaging']),
                    'validation_status' => 'validated',
                    'validation_score' => 4.8
                ],
                [
                    'step_type' => 'logistics',
                    'step_name' => 'Dağıtım',
                    'step_description' => 'Ürünlerin perakende noktalarına dağıtımı',
                    'step_order' => 5,
                    'location_coordinates' => json_encode(['lat' => 39.9334, 'lng' => 32.8597]), // Ankara coordinates
                    'address' => 'Dağıtım Merkezi, Ankara, Türkiye',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-5 days')),
                    'end_date' => date('Y-m-d H:i:s'),
                    'duration_hours' => 120,
                    'carbon_emissions' => 22.4,
                    'water_usage' => 0.0,
                    'energy_consumption' => 38.5,
                    'waste_generated' => 1.5,
                    'renewable_energy_percentage' => 30.0,
                    'worker_count' => 8,
                    'average_wage' => 25.50,
                    'working_hours_per_day' => 8.0,
                    'safety_incidents' => 0,
                    'worker_satisfaction_score' => 4.1,
                    'transport_mode' => 'truck',
                    'distance_km' => 420.0,
                    'fuel_type' => 'diesel',
                    'fuel_consumption' => 32.8,
                    'certificates' => json_encode(['Green Logistics']),
                    'validation_status' => 'validated',
                    'validation_score' => 4.4
                ]
            ];
        } elseif (strpos($productCode, 'ELEK') !== false) {
            // Electronic product supply chain
            $supplyChainSteps = [
                [
                    'step_type' => 'raw_material',
                    'step_name' => 'Ham Madde Temini',
                    'step_description' => 'Çeşitli metaller ve plastiklerin temini',
                    'step_order' => 1,
                    'location_coordinates' => json_encode(['lat' => 31.2304, 'lng' => 121.4737]), // Shanghai coordinates
                    'address' => 'Ham Madde Tedarikçisi, Shanghai, Çin',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-50 days')),
                    'end_date' => date('Y-m-d H:i:s', strtotime('-45 days')),
                    'duration_hours' => 120,
                    'carbon_emissions' => 18.5,
                    'water_usage' => 320.0,
                    'energy_consumption' => 125.6,
                    'waste_generated' => 15.8,
                    'renewable_energy_percentage' => 25.0,
                    'worker_count' => 40,
                    'average_wage' => 18.50,
                    'working_hours_per_day' => 10.0,
                    'safety_incidents' => 2,
                    'worker_satisfaction_score' => 3.2,
                    'certificates' => json_encode(['ISO 14001', 'Conflict-Free Minerals']),
                    'validation_status' => 'validated',
                    'validation_score' => 4.2
                ],
                [
                    'step_type' => 'manufacturing',
                    'step_name' => 'Üretim',
                    'step_description' => 'Elektronik bileşenlerin montajı ve test edilmesi',
                    'step_order' => 2,
                    'location_coordinates' => json_encode(['lat' => 22.3964, 'lng' => 114.1095]), // Hong Kong coordinates
                    'address' => 'Üretim Tesisi, Hong Kong, Çin',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-40 days')),
                    'end_date' => date('Y-m-d H:i:s', strtotime('-25 days')),
                    'duration_hours' => 360,
                    'carbon_emissions' => 45.2,
                    'water_usage' => 580.0,
                    'energy_consumption' => 320.8,
                    'waste_generated' => 32.5,
                    'renewable_energy_percentage' => 35.0,
                    'worker_count' => 150,
                    'average_wage' => 22.75,
                    'working_hours_per_day' => 10.0,
                    'safety_incidents' => 3,
                    'worker_satisfaction_score' => 3.5,
                    'certificates' => json_encode(['ISO 9001', 'ISO 14001', 'SA8000']),
                    'validation_status' => 'validated',
                    'validation_score' => 4.0
                ],
                [
                    'step_type' => 'packaging',
                    'step_name' => 'Ambalajlama',
                    'step_description' => 'Ürünlerin geri dönüştürülmüş ambalaj malzemeleriyle paketlenmesi',
                    'step_order' => 3,
                    'location_coordinates' => json_encode(['lat' => 39.9042, 'lng' => 116.4074]), // Beijing coordinates
                    'address' => 'Ambalaj Tesisi, Beijing, Çin',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-20 days')),
                    'end_date' => date('Y-m-d H:i:s', strtotime('-15 days')),
                    'duration_hours' => 120,
                    'carbon_emissions' => 8.7,
                    'water_usage' => 65.0,
                    'energy_consumption' => 58.3,
                    'waste_generated' => 12.2,
                    'renewable_energy_percentage' => 45.0,
                    'worker_count' => 25,
                    'average_wage' => 20.30,
                    'working_hours_per_day' => 8.0,
                    'safety_incidents' => 1,
                    'worker_satisfaction_score' => 3.8,
                    'certificates' => json_encode(['FSC', 'Recycled Packaging']),
                    'validation_status' => 'validated',
                    'validation_score' => 4.3
                ],
                [
                    'step_type' => 'logistics',
                    'step_name' => 'Uluslararası Nakliye',
                    'step_description' => 'Ürünlerin Türkiye\'ye deniz yoluyla sevkiyatı',
                    'step_order' => 4,
                    'location_coordinates' => json_encode(['lat' => 40.9937, 'lng' => 29.0226]), // Istanbul Port coordinates
                    'address' => 'İstanbul Limanı, Türkiye',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-10 days')),
                    'end_date' => date('Y-m-d H:i:s'),
                    'duration_hours' => 480,
                    'carbon_emissions' => 185.6,
                    'water_usage' => 0.0,
                    'energy_consumption' => 0.0,
                    'waste_generated' => 3.5,
                    'renewable_energy_percentage' => 0.0,
                    'worker_count' => 15,
                    'average_wage' => 28.50,
                    'working_hours_per_day' => 12.0,
                    'safety_incidents' => 0,
                    'worker_satisfaction_score' => 4.0,
                    'transport_mode' => 'ship',
                    'distance_km' => 9500.0,
                    'fuel_type' => 'heavy fuel oil',
                    'fuel_consumption' => 250.8,
                    'certificates' => json_encode(['Green Shipping']),
                    'validation_status' => 'validated',
                    'validation_score' => 3.8
                ]
            ];
        } elseif (strpos($productCode, 'KOZ') !== false) {
            // Cosmetic product supply chain
            $supplyChainSteps = [
                [
                    'step_type' => 'raw_material',
                    'step_name' => 'Doğal Bileşen Temini',
                    'step_description' => 'Organik bitki özlerinin ve doğal hammaddelerin temini',
                    'step_order' => 1,
                    'location_coordinates' => json_encode(['lat' => 36.8969, 'lng' => 30.7133]), // Antalya coordinates
                    'address' => 'Bitki Yetiştirme Alanı, Antalya, Türkiye',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-40 days')),
                    'end_date' => date('Y-m-d H:i:s', strtotime('-35 days')),
                    'duration_hours' => 120,
                    'carbon_emissions' => 3.2,
                    'water_usage' => 280.0,
                    'energy_consumption' => 18.5,
                    'waste_generated' => 1.8,
                    'renewable_energy_percentage' => 65.0,
                    'worker_count' => 12,
                    'average_wage' => 24.50,
                    'working_hours_per_day' => 8.0,
                    'safety_incidents' => 0,
                    'worker_satisfaction_score' => 4.5,
                    'certificates' => json_encode(['Organic Certification', 'Fair Trade']),
                    'validation_status' => 'validated',
                    'validation_score' => 4.9
                ],
                [
                    'step_type' => 'processing',
                    'step_name' => 'Ekstraksiyon ve İşleme',
                    'step_description' => 'Doğal bileşenlerin ekstraksiyonu ve işleme',
                    'step_order' => 2,
                    'location_coordinates' => json_encode(['lat' => 41.0082, 'lng' => 28.9784]), // Istanbul coordinates
                    'address' => 'İşleme Tesisi, Istanbul, Türkiye',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-30 days')),
                    'end_date' => date('Y-m-d H:i:s', strtotime('-20 days')),
                    'duration_hours' => 240,
                    'carbon_emissions' => 9.8,
                    'water_usage' => 420.0,
                    'energy_consumption' => 65.2,
                    'waste_generated' => 6.5,
                    'renewable_energy_percentage' => 55.0,
                    'worker_count' => 18,
                    'average_wage' => 27.75,
                    'working_hours_per_day' => 8.0,
                    'safety_incidents' => 0,
                    'worker_satisfaction_score' => 4.3,
                    'certificates' => json_encode(['ISO 22716', 'GMP']),
                    'validation_status' => 'validated',
                    'validation_score' => 4.7
                ],
                [
                    'step_type' => 'manufacturing',
                    'step_name' => 'Formülasyon ve Üretim',
                    'step_description' => 'Ürünlerin formülasyonu ve üretimi',
                    'step_order' => 3,
                    'location_coordinates' => json_encode(['lat' => 38.4237, 'lng' => 27.1428]), // Izmir coordinates
                    'address' => 'Üretim Tesisi, Izmir, Türkiye',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-18 days')),
                    'end_date' => date('Y-m-d H:i:s', strtotime('-12 days')),
                    'duration_hours' => 144,
                    'carbon_emissions' => 7.5,
                    'water_usage' => 180.0,
                    'energy_consumption' => 42.8,
                    'waste_generated' => 4.2,
                    'renewable_energy_percentage' => 60.0,
                    'worker_count' => 15,
                    'average_wage' => 26.30,
                    'working_hours_per_day' => 8.0,
                    'safety_incidents' => 0,
                    'worker_satisfaction_score' => 4.4,
                    'certificates' => json_encode(['ISO 9001', 'Cruelty Free', 'Vegan']),
                    'validation_status' => 'validated',
                    'validation_score' => 4.8
                ],
                [
                    'step_type' => 'packaging',
                    'step_name' => 'Ambalajlama',
                    'step_description' => 'Ürünlerin geri dönüştürülmüş ambalaj malzemeleriyle paketlenmesi',
                    'step_order' => 4,
                    'location_coordinates' => json_encode(['lat' => 41.0082, 'lng' => 28.9784]), // Istanbul coordinates
                    'address' => 'Ambalaj Tesisi, Istanbul, Türkiye',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-10 days')),
                    'end_date' => date('Y-m-d H:i:s', strtotime('-5 days')),
                    'duration_hours' => 120,
                    'carbon_emissions' => 2.8,
                    'water_usage' => 35.0,
                    'energy_consumption' => 22.7,
                    'waste_generated' => 2.5,
                    'renewable_energy_percentage' => 75.0,
                    'worker_count' => 10,
                    'average_wage' => 25.50,
                    'working_hours_per_day' => 8.0,
                    'safety_incidents' => 0,
                    'worker_satisfaction_score' => 4.6,
                    'certificates' => json_encode(['FSC', 'Recycled Packaging']),
                    'validation_status' => 'validated',
                    'validation_score' => 4.9
                ],
                [
                    'step_type' => 'logistics',
                    'step_name' => 'Dağıtım',
                    'step_description' => 'Ürünlerin perakende noktalarına dağıtımı',
                    'step_order' => 5,
                    'location_coordinates' => json_encode(['lat' => 39.9334, 'lng' => 32.8597]), // Ankara coordinates
                    'address' => 'Dağıtım Merkezi, Ankara, Türkiye',
                    'start_date' => date('Y-m-d H:i:s', strtotime('-3 days')),
                    'end_date' => date('Y-m-d H:i:s'),
                    'duration_hours' => 96,
                    'carbon_emissions' => 18.6,
                    'water_usage' => 0.0,
                    'energy_consumption' => 32.5,
                    'waste_generated' => 1.2,
                    'renewable_energy_percentage' => 35.0,
                    'worker_count' => 8,
                    'average_wage' => 24.50,
                    'working_hours_per_day' => 8.0,
                    'safety_incidents' => 0,
                    'worker_satisfaction_score' => 4.2,
                    'transport_mode' => 'truck',
                    'distance_km' => 450.0,
                    'fuel_type' => 'diesel',
                    'fuel_consumption' => 28.8,
                    'certificates' => json_encode(['Green Logistics']),
                    'validation_status' => 'validated',
                    'validation_score' => 4.5
                ]
            ];
        }
        
        // Insert supply chain steps
        foreach ($supplyChainSteps as $step) {
            $stepSql = "INSERT INTO supply_chain_steps (
                uuid, product_batch_id, company_id, step_type, step_name, step_description, 
                step_order, location_coordinates, address, start_date, end_date, duration_hours,
                carbon_emissions, water_usage, energy_consumption, waste_generated, 
                renewable_energy_percentage, worker_count, average_wage, working_hours_per_day,
                safety_incidents, worker_satisfaction_score, certificates, validation_status, 
                validation_score, transport_mode, distance_km, fuel_type, fuel_consumption
            ) VALUES (
                UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )";
            
            $db->execute($stepSql, [
                $batchId,
                $companyId,
                $step['step_type'],
                $step['step_name'],
                $step['step_description'],
                $step['step_order'],
                $step['location_coordinates'],
                $step['address'],
                $step['start_date'],
                $step['end_date'],
                $step['duration_hours'],
                $step['carbon_emissions'],
                $step['water_usage'],
                $step['energy_consumption'],
                $step['waste_generated'],
                $step['renewable_energy_percentage'],
                $step['worker_count'],
                $step['average_wage'],
                $step['working_hours_per_day'],
                $step['safety_incidents'],
                $step['worker_satisfaction_score'],
                $step['certificates'],
                $step['validation_status'],
                $step['validation_score'],
                $step['transport_mode'] ?? null,
                $step['distance_km'] ?? null,
                $step['fuel_type'] ?? null,
                $step['fuel_consumption'] ?? null
            ]);
        }
        
        echo "Added " . count($supplyChainSteps) . " supply chain steps for: " . $productName . "\n";
    }
    
    echo "Supply chain data addition completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>