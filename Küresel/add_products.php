<?php
// Add all products to the database

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
    
    // Get the company ID for "Test Food Company"
    $sql = "SELECT id FROM companies WHERE company_name = 'Test Food Company'";
    $company = $db->fetchRow($sql);
    
    if (!$company) {
        throw new Exception("Test Food Company not found in database");
    }
    
    $companyId = $company['id'];
    echo "Using company ID: " . $companyId . " (Test Food Company)\n";
    
    // Define the products to add
    $products = [
        // Gıda Ürünleri (Food Products)
        [
            'product_name' => 'Organik Zeytinyağı',
            'product_code' => 'PRD-GIDA-001',
            'barcode' => '8691234567890',
            'category' => 'Gıda',
            'brand' => 'Organik Tarım',
            'description' => '100% organik, soğuk sıkım zeytinyağı',
            'weight' => 0.5, // kg
            'volume' => 0.5, // liters
            'packaging_type' => 'Cam Şişe',
            'shelf_life' => 730, // days
            'origin_country' => 'Türkiye',
            'origin_region' => 'Ege',
            'harvest_season' => '2025 Sonbahar',
            'product_images' => ['/Küresel/assets/images/products/organik-zeytinyagi.jpg'],
        ],
        [
            'product_name' => 'Bal',
            'product_code' => 'PRD-GIDA-002',
            'barcode' => '8691234567891',
            'category' => 'Gıda',
            'brand' => 'Doğal Arı Ürünleri',
            'description' => 'Saf çiçek balı',
            'weight' => 0.4, // kg
            'volume' => 0.4, // liters
            'packaging_type' => 'Cam Kavanoz',
            'shelf_life' => 1095, // days
            'origin_country' => 'Türkiye',
            'origin_region' => 'Karadeniz',
            'harvest_season' => '2025 Yaz',
            'product_images' => ['/Küresel/assets/images/products/bal.jpg'],
        ],
        [
            'product_name' => 'Ton Balığı Konservesi',
            'product_code' => 'PRD-GIDA-003',
            'barcode' => '8691234567892',
            'category' => 'Gıda',
            'brand' => 'Deniz Ürünleri',
            'description' => 'Sızma zeytinyağlı ton balığı konservesi',
            'weight' => 0.16, // kg
            'volume' => 0.16, // liters
            'packaging_type' => 'Teneke Kutu',
            'shelf_life' => 1460, // days
            'origin_country' => 'Türkiye',
            'origin_region' => 'Marmara',
            'harvest_season' => '2025 İlkbahar',
            'product_images' => ['/Küresel/assets/images/products/ton-baligi.jpg'],
        ],
        
        // Tekstil Ürünleri (Textile Products)
        [
            'product_name' => 'Organik Pamuk Tişört',
            'product_code' => 'PRD-TEKSTIL-001',
            'barcode' => '8691234567893',
            'category' => 'Tekstil',
            'brand' => 'Eko Giyim',
            'description' => '100% organik pamuktan üretilmiş tişört',
            'weight' => 0.2, // kg
            'packaging_type' => 'Karton Kutu',
            'shelf_life' => 3650, // days
            'origin_country' => 'Türkiye',
            'origin_region' => 'Ege',
            'harvest_season' => '2025 Yaz',
            'product_images' => ['/Küresel/assets/images/products/tisort.jpg'],
        ],
        [
            'product_name' => 'Kot Pantolon',
            'product_code' => 'PRD-TEKSTIL-002',
            'barcode' => '8691234567894',
            'category' => 'Tekstil',
            'brand' => 'Denim Style',
            'description' => 'Klasik kesim kot pantolon',
            'weight' => 0.8, // kg
            'packaging_type' => 'Plastik Poşet',
            'shelf_life' => 3650, // days
            'origin_country' => 'Türkiye',
            'origin_region' => 'Marmara',
            'harvest_season' => '2025 Sonbahar',
            'product_images' => ['/Küresel/assets/images/products/kot-pantolon.jpg'],
        ],
        
        // Elektronik Ürünler (Electronic Products)
        [
            'product_name' => 'Akıllı Telefon',
            'product_code' => 'PRD-ELEK-001',
            'barcode' => '8691234567895',
            'category' => 'Elektronik',
            'brand' => 'TechBrand',
            'description' => '6.5 inch ekran, 128GB depolama, çift kamera',
            'weight' => 0.19, // kg
            'packaging_type' => 'Karton Kutu',
            'shelf_life' => 1825, // days
            'origin_country' => 'Çin',
            'origin_region' => 'Shenzhen',
            'harvest_season' => '2025 Yaz',
            'product_images' => ['/Küresel/assets/images/products/akilli-telefon.jpg'],
        ],
        [
            'product_name' => 'Dizüstü Bilgisayar',
            'product_code' => 'PRD-ELEK-002',
            'barcode' => '8691234567896',
            'category' => 'Elektronik',
            'brand' => 'TechBrand',
            'description' => '15.6 inch ekran, 512GB SSD, 16GB RAM',
            'weight' => 1.8, // kg
            'packaging_type' => 'Karton Kutu',
            'shelf_life' => 1825, // days
            'origin_country' => 'Çin',
            'origin_region' => 'Shanghai',
            'harvest_season' => '2025 İlkbahar',
            'product_images' => ['/Küresel/assets/images/products/dizustu-bilgisayar.jpg'],
        ],
        
        // Kozmetik Ürünler (Cosmetic Products)
        [
            'product_name' => 'Doğal Şampuan',
            'product_code' => 'PRD-KOZ-001',
            'barcode' => '8691234567897',
            'category' => 'Kozmetik',
            'brand' => 'Doğal Bakım',
            'description' => 'Organik bitki özleriyle zenginleştirilmiş şampuan',
            'weight' => 0.3, // kg
            'volume' => 0.3, // liters
            'packaging_type' => 'Plastik Şişe',
            'shelf_life' => 1095, // days
            'origin_country' => 'Türkiye',
            'origin_region' => 'Ege',
            'harvest_season' => '2025 Yaz',
            'product_images' => ['/Küresel/assets/images/products/dogal-sampuan.jpg'],
        ],
        [
            'product_name' => 'Organik Krem',
            'product_code' => 'PRD-KOZ-002',
            'barcode' => '8691234567898',
            'category' => 'Kozmetik',
            'brand' => 'Doğal Bakım',
            'description' => 'Organik içerikli nemlendirici yüz kremi',
            'weight' => 0.05, // kg
            'volume' => 0.05, // liters
            'packaging_type' => 'Plastik Kavanoz',
            'shelf_life' => 730, // days
            'origin_country' => 'Türkiye',
            'origin_region' => 'Akdeniz',
            'harvest_season' => '2025 Sonbahar',
            'product_images' => ['/Küresel/assets/images/products/organik-krem.jpg'],
        ]
    ];
    
    echo "Adding " . count($products) . " products...\n";
    
    // Add each product
    foreach ($products as $productData) {
        try {
            // Check if product already exists
            $sql = "SELECT id FROM products WHERE product_code = ?";
            $existingProduct = $db->fetchRow($sql, [$productData['product_code']]);
            
            if ($existingProduct) {
                echo "Product " . $productData['product_name'] . " already exists, skipping...\n";
                continue;
            }
            
            // Add company_id to product data
            $productData['company_id'] = $companyId;
            
            // Create product instance and add product
            $product = new Product();
            $productId = $product->create($productData);
            
            echo "Added product: " . $productData['product_name'] . " (ID: " . $productId . ")\n";
        } catch (Exception $e) {
            echo "Error adding product " . $productData['product_name'] . ": " . $e->getMessage() . "\n";
        }
    }
    
    // Verify the products were added
    $sql = "SELECT COUNT(*) as count FROM products";
    $result = $db->fetchRow($sql);
    echo "\nTotal products in database after adding: " . $result['count'] . "\n";
    
    echo "Product addition completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>