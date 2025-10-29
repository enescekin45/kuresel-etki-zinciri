<?php
// Enhance product details with complete information including supply chain, impact scores, and certificates

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
    
    // Get all products
    $sql = "SELECT id, product_name, product_code FROM products ORDER BY id";
    $products = $db->fetchAll($sql);
    
    echo "Enhancing details for " . count($products) . " products...\n";
    
    foreach ($products as $productData) {
        $productId = $productData['id'];
        $productName = $productData['product_name'];
        $productCode = $productData['product_code'];
        
        echo "Enhancing product: " . $productName . " (" . $productCode . ")\n";
        
        // Update product with more detailed information
        $updateData = [];
        
        // Set detailed information based on product type
        switch ($productCode) {
            case 'PRD-GIDA-001': // Organik Zeytinyağı
                $updateData = [
                    'subcategory' => 'Yağlar ve Sıvı Yağlar',
                    'brand' => 'Organik Tarım',
                    'description' => '100% organik, soğuk sıkım zeytinyağı. Ege bölgesi zeytinlerinden elde edilmiştir. Antioksidan açısından zengin, kalp damar sağlığına faydalıdır.',
                    'weight' => 0.5, // kg
                    'volume' => 0.5, // liters
                    'dimensions' => json_encode(['length' => 10, 'width' => 7, 'height' => 25]),
                    'packaging_type' => 'Cam Şişe',
                    'shelf_life' => 730, // days
                    'origin_country' => 'Türkiye',
                    'origin_region' => 'Ege',
                    'harvest_season' => '2025 Sonbahar',
                    'product_images' => json_encode(['/Küresel/assets/images/products/organik-zeytinyagi.jpg']),
                ];
                break;
                
            case 'PRD-GIDA-002': // Bal
                $updateData = [
                    'subcategory' => 'Arı Ürünleri',
                    'brand' => 'Doğal Arı Ürünleri',
                    'description' => 'Saf çiçek balı. Karadeniz bölgesi çiçeklerinden elde edilmiştir. Enerji verici, antioksidan açısından zengin.',
                    'weight' => 0.4, // kg
                    'volume' => 0.4, // liters
                    'dimensions' => json_encode(['length' => 8, 'width' => 8, 'height' => 12]),
                    'packaging_type' => 'Cam Kavanoz',
                    'shelf_life' => 1095, // days
                    'origin_country' => 'Türkiye',
                    'origin_region' => 'Karadeniz',
                    'harvest_season' => '2025 Yaz',
                    'product_images' => json_encode(['/Küresel/assets/images/products/bal.jpg']),
                ];
                break;
                
            case 'PRD-GIDA-003': // Ton Balığı Konservesi
                $updateData = [
                    'subcategory' => 'Konserve Ürünler',
                    'brand' => 'Deniz Ürünleri',
                    'description' => 'Sızma zeytinyağlı ton balığı konservesi. Omega-3 yağ asitleri açısından zengin. Protein kaynağıdır.',
                    'weight' => 0.16, // kg
                    'volume' => 0.16, // liters
                    'dimensions' => json_encode(['length' => 7, 'width' => 5, 'height' => 3]),
                    'packaging_type' => 'Teneke Kutu',
                    'shelf_life' => 1460, // days
                    'origin_country' => 'Türkiye',
                    'origin_region' => 'Marmara',
                    'harvest_season' => '2025 İlkbahar',
                    'product_images' => json_encode(['/Küresel/assets/images/products/ton-baligi.jpg']),
                ];
                break;
                
            case 'PRD-TEKSTIL-001': // Organik Pamuk Tişört
                $updateData = [
                    'subcategory' => 'Üst Giyim',
                    'brand' => 'Eko Giyim',
                    'description' => '100% organik pamuktan üretilmiş tişört. Çocuk ve yetişkin bedenlerinde mevcuttur. Çevreye duyarlı üretim.',
                    'weight' => 0.2, // kg
                    'dimensions' => json_encode(['length' => 30, 'width' => 20, 'height' => 2]),
                    'packaging_type' => 'Karton Kutu',
                    'shelf_life' => 3650, // days
                    'origin_country' => 'Türkiye',
                    'origin_region' => 'Ege',
                    'harvest_season' => '2025 Yaz',
                    'product_images' => json_encode(['/Küresel/assets/images/products/tisort.jpg']),
                ];
                break;
                
            case 'PRD-TEKSTIL-002': // Kot Pantolon
                $updateData = [
                    'subcategory' => 'Alt Giyim',
                    'brand' => 'Denim Style',
                    'description' => 'Klasik kesim kot pantolon. %100 pamuk içeriği. Su tasarruflu boyama tekniği ile üretilmiştir.',
                    'weight' => 0.8, // kg
                    'dimensions' => json_encode(['length' => 40, 'width' => 30, 'height' => 3]),
                    'packaging_type' => 'Plastik Poşet',
                    'shelf_life' => 3650, // days
                    'origin_country' => 'Türkiye',
                    'origin_region' => 'Marmara',
                    'harvest_season' => '2025 Sonbahar',
                    'product_images' => json_encode(['/Küresel/assets/images/products/kot-pantolon.jpg']),
                ];
                break;
                
            case 'PRD-ELEK-001': // Akıllı Telefon
                $updateData = [
                    'subcategory' => 'Mobil Cihazlar',
                    'brand' => 'TechBrand',
                    'description' => '6.5 inch ekran, 128GB depolama, çift kamera. Enerji verimli işlemci. Geri dönüştürülmüş plastik kullanılmıştır.',
                    'weight' => 0.19, // kg
                    'dimensions' => json_encode(['length' => 16, 'width' => 7.5, 'height' => 0.8]),
                    'packaging_type' => 'Karton Kutu',
                    'shelf_life' => 1825, // days
                    'origin_country' => 'Çin',
                    'origin_region' => 'Shenzhen',
                    'harvest_season' => '2025 Yaz', // Manufacturing season
                    'product_images' => json_encode(['/Küresel/assets/images/products/akilli-telefon.jpg']),
                ];
                break;
                
            case 'PRD-ELEK-002': // Dizüstü Bilgisayar
                $updateData = [
                    'subcategory' => 'Bilgisayarlar',
                    'brand' => 'TechBrand',
                    'description' => '15.6 inch ekran, 512GB SSD, 16GB RAM. Enerji tasarruflu işlemci. Geri dönüştürülmüş alüminyum alaşım kasası.',
                    'weight' => 1.8, // kg
                    'dimensions' => json_encode(['length' => 36, 'width' => 25, 'height' => 2]),
                    'packaging_type' => 'Karton Kutu',
                    'shelf_life' => 1825, // days
                    'origin_country' => 'Çin',
                    'origin_region' => 'Shanghai',
                    'harvest_season' => '2025 İlkbahar', // Manufacturing season
                    'product_images' => json_encode(['/Küresel/assets/images/products/dizustu-bilgisayar.jpg']),
                ];
                break;
                
            case 'PRD-KOZ-001': // Doğal Şampuan
                $updateData = [
                    'subcategory' => 'Saç Bakım Ürünleri',
                    'brand' => 'Doğal Bakım',
                    'description' => 'Organik bitki özleriyle zenginleştirilmiş şampuan. Sodyum lauril sülfat içermez. Vegan ve cruelty-free.',
                    'weight' => 0.3, // kg
                    'volume' => 0.3, // liters
                    'dimensions' => json_encode(['length' => 15, 'width' => 7, 'height' => 20]),
                    'packaging_type' => 'Plastik Şişe',
                    'shelf_life' => 1095, // days
                    'origin_country' => 'Türkiye',
                    'origin_region' => 'Ege',
                    'harvest_season' => '2025 Yaz',
                    'product_images' => json_encode(['/Küresel/assets/images/products/dogal-sampuan.jpg']),
                ];
                break;
                
            case 'PRD-KOZ-002': // Organik Krem
                $updateData = [
                    'subcategory' => 'Yüz Bakım Ürünleri',
                    'brand' => 'Doğal Bakım',
                    'description' => 'Organik içerikli nemlendirici yüz kremi. Aloe vera ve jojoba yağı içerir. Hassas ciltler için uygundur.',
                    'weight' => 0.05, // kg
                    'volume' => 0.05, // liters
                    'dimensions' => json_encode(['length' => 10, 'width' => 6, 'height' => 12]),
                    'packaging_type' => 'Plastik Kavanoz',
                    'shelf_life' => 730, // days
                    'origin_country' => 'Türkiye',
                    'origin_region' => 'Akdeniz',
                    'harvest_season' => '2025 Sonbahar',
                    'product_images' => json_encode(['/Küresel/assets/images/products/organik-krem.jpg']),
                ];
                break;
                
            default:
                echo "No specific details for product: " . $productName . "\n";
                continue 2; // Skip to next product
        }
        
        // Update the product with detailed information
        $setClause = [];
        $params = [];
        
        foreach ($updateData as $key => $value) {
            $setClause[] = "{$key} = ?";
            $params[] = $value;
        }
        
        $params[] = $productId; // For WHERE clause
        
        $sql = "UPDATE products SET " . implode(', ', $setClause) . " WHERE id = ?";
        $db->execute($sql, $params);
        
        echo "Updated product: " . $productName . "\n";
        
        // Create a batch for each product
        $batchSql = "SELECT COUNT(*) as count FROM product_batches WHERE product_id = ?";
        $batchResult = $db->fetchRow($batchSql, [$productId]);
        
        if ($batchResult['count'] == 0) {
            $batchData = [
                'batch_number' => 'BATCH-' . $productId . '-' . date('Ym'),
                'production_date' => date('Y-m-d', strtotime('-30 days')),
                'expiry_date' => date('Y-m-d', strtotime('+' . ($updateData['shelf_life'] ?? 365) . ' days')),
                'quantity' => 1000,
                'unit' => 'pieces',
                'production_facility' => $updateData['origin_region'] ?? 'Factory',
                'production_line' => 'Line-' . $productId,
                'quality_grade' => 'A',
                'status' => 'in_production'
            ];
            
            $batchSql = "INSERT INTO product_batches (uuid, product_id, batch_number, production_date, expiry_date, quantity, unit, production_facility, production_line, quality_grade, status) VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $db->execute($batchSql, [
                $productId,
                $batchData['batch_number'],
                $batchData['production_date'],
                $batchData['expiry_date'],
                $batchData['quantity'],
                $batchData['unit'],
                $batchData['production_facility'],
                $batchData['production_line'],
                $batchData['quality_grade'],
                $batchData['status']
            ]);
            
            echo "Created batch for product: " . $productName . "\n";
        }
    }
    
    echo "Product details enhancement completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>