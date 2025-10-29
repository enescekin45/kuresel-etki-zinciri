<?php
// Add certificates information to products

// Define ROOT_DIR constant
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

// Include required files
require_once ROOT_DIR . '/config/database.php';
require_once ROOT_DIR . '/classes/Database.php';

try {
    $db = Database::getInstance();
    
    // Get all products
    $sql = "SELECT id, product_name, product_code FROM products ORDER BY id";
    $products = $db->fetchAll($sql);
    
    echo "Adding certificates for " . count($products) . " products...\n";
    
    foreach ($products as $productData) {
        $productId = $productData['id'];
        $productName = $productData['product_name'];
        $productCode = $productData['product_code'];
        
        echo "Adding certificates for: " . $productName . " (" . $productCode . ")\n";
        
        // Generate certificates based on product category
        $certificates = [];
        
        if (strpos($productCode, 'GIDA') !== false) {
            // Food product certificates
            $certificates = [
                [
                    'name' => 'Organik Tarım Sertifikası',
                    'issuer' => 'Türk Standartları Enstitüsü',
                    'validity' => '2025-12-31',
                    'certificate_number' => 'TR-ORG-2025-001',
                    'description' => 'Ürünün %100 organik içerikli olduğunu ve organik tarım standartlarına uygun üretildiğini belgeler.'
                ],
                [
                    'name' => 'Çevre Dostu Ürün Sertifikası',
                    'issuer' => 'Çevre ve Şehircilik Bakanlığı',
                    'validity' => '2026-06-30',
                    'certificate_number' => 'ÇS-ÇEV-2025-123',
                    'description' => 'Ürünün üretim sürecinin çevre dostu olduğunu ve sürdürülebilirlik ilkelerine uygun olduğunu belgeler.'
                ],
                [
                    'name' => 'Kalite Yönetim Sistemi',
                    'issuer' => 'ISO',
                    'validity' => '2026-03-15',
                    'certificate_number' => 'ISO-9001-2025-Q1',
                    'description' => 'Ürünün kalite yönetim sistemi standartlarına uygun üretildiğini belgeler.'
                ],
                [
                    'name' => 'Gıda Güvenliği Sertifikası',
                    'issuer' => 'Türk Akreditasyon Kurumu',
                    'validity' => '2026-09-30',
                    'certificate_number' => 'TAK-FOOD-2025-045',
                    'description' => 'Ürünün gıda güvenliği standartlarına uygun üretildiğini ve tüketicinin sağlığı için güvenli olduğunu belgeler.'
                ]
            ];
        } elseif (strpos($productCode, 'TEKSTIL') !== false) {
            // Textile product certificates
            $certificates = [
                [
                    'name' => 'Organik Pamuk Sertifikası',
                    'issuer' => 'Organic Cotton Standards',
                    'validity' => '2026-02-28',
                    'certificate_number' => 'OCS-TEX-2025-078',
                    'description' => 'Ürünün %100 organik pamuk kullanılarak üretildiğini belgeler.'
                ],
                [
                    'name' => 'Adil Ticaret Sertifikası',
                    'issuer' => 'Fair Trade International',
                    'validity' => '2026-05-31',
                    'certificate_number' => 'FTI-TEX-2025-034',
                    'description' => 'Ürünün üretim sürecinde adil ticaret ilkelerinin uygulandığını ve işçilerin haklarının korunduğunu belgeler.'
                ],
                [
                    'name' => 'Su Tasarrufu Sertifikası',
                    'issuer' => 'Better Cotton Initiative',
                    'validity' => '2026-08-31',
                    'certificate_number' => 'BCI-WATER-2025-012',
                    'description' => 'Ürünün üretim sürecinde su tasarrufu sağlandığını ve çevre dostu yöntemlerin uygulandığını belgeler.'
                ],
                [
                    'name' => 'Çalışan Hakları Sertifikası',
                    'issuer' => 'Social Accountability International',
                    'validity' => '2026-11-30',
                    'certificate_number' => 'SA8000-TEX-2025-056',
                    'description' => 'Ürünün üretim sürecinde çalışan haklarının korunduğunu ve güvenli çalışma koşullarının sağlandığını belgeler.'
                ]
            ];
        } elseif (strpos($productCode, 'ELEK') !== false) {
            // Electronic product certificates
            $certificates = [
                [
                    'name' => 'Enerji Verimliliği Sertifikası',
                    'issuer' => 'Energy Star',
                    'validity' => '2026-01-31',
                    'certificate_number' => 'ENERGY-2025-123',
                    'description' => 'Ürünün enerji verimliliği standartlarına uygun olduğunu ve düşük enerji tüketimine sahip olduğunu belgeler.'
                ],
                [
                    'name' => 'Geri Dönüşüm Uyumluluğu Sertifikası',
                    'issuer' => 'EPEAT',
                    'validity' => '2026-04-30',
                    'certificate_number' => 'EPEAT-2025-045',
                    'description' => 'Ürünün geri dönüştürülebilir malzemeler içerdiğini ve çevre dostu üretim süreçlerine uygun üretildiğini belgeler.'
                ],
                [
                    'name' => 'Elektromanyetik Uyumluluk Sertifikası',
                    'issuer' => 'CE',
                    'validity' => '2026-07-31',
                    'certificate_number' => 'CE-2025-078',
                    'description' => 'Ürünün elektromanyetik uyumluluk standartlarına uygun olduğunu ve diğer elektronik cihazları etkilemeyeceğini belgeler.'
                ],
                [
                    'name' => 'Güvenlik Sertifikası',
                    'issuer' => 'TÜV',
                    'validity' => '2026-10-31',
                    'certificate_number' => 'TÜV-SAFETY-2025-091',
                    'description' => 'Ürünün güvenlik standartlarına uygun üretildiğini ve kullanıcı için güvenli olduğunu belgeler.'
                ]
            ];
        } elseif (strpos($productCode, 'KOZ') !== false) {
            // Cosmetic product certificates
            $certificates = [
                [
                    'name' => 'Organik Kozmetik Sertifikası',
                    'issuer' => 'COSMOS',
                    'validity' => '2026-03-31',
                    'certificate_number' => 'COSMOS-2025-023',
                    'description' => 'Ürünün %95 oranında organik içerik barındırdığını ve doğal içerikli olduğunu belgeler.'
                ],
                [
                    'name' => 'Vegan Ürün Sertifikası',
                    'issuer' => 'The Vegan Society',
                    'validity' => '2026-06-30',
                    'certificate_number' => 'VEGAN-2025-056',
                    'description' => 'Ürünün hayvansal içerik barındırmadığını ve hayvan testi yapılmadığını belgeler.'
                ],
                [
                    'name' => 'Cruelty Free Sertifikası',
                    'issuer' => 'Leaping Bunny',
                    'validity' => '2026-09-30',
                    'certificate_number' => 'CRUELTY-2025-089',
                    'description' => 'Ürünün üretim sürecinde hayvan testi yapılmadığını ve hayvan haklarına saygı duyulduğunu belgeler.'
                ],
                [
                    'name' => 'Doğal İçerik Sertifikası',
                    'issuer' => 'Natural Products Association',
                    'validity' => '2026-12-31',
                    'certificate_number' => 'NPA-2025-112',
                    'description' => 'Ürünün doğal içerik barındırdığını ve sentetik kimyasallar içermediğini belgeler.'
                ]
            ];
        }
        
        // Update product with certificates information in the documentation field
        $certificatesJson = json_encode($certificates);
        $updateSql = "UPDATE products SET documentation = ? WHERE id = ?";
        $db->execute($updateSql, [$certificatesJson, $productId]);
        
        echo "Added " . count($certificates) . " certificates for: " . $productName . "\n";
    }
    
    echo "Certificates addition completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>