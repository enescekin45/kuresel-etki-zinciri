<?php
// Fix image paths in the database for all products

// Define ROOT_DIR constant
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

// Include required files
require_once ROOT_DIR . '/config/database.php';
require_once ROOT_DIR . '/classes/Database.php';

try {
    $db = Database::getInstance();
    
    // Define correct image paths for all products
    $productImages = [
        'Organik Zeytinyağı' => '/Küresel/assets/images/products/organik-zeytinyagi.svg',
        'Bal' => '/Küresel/assets/images/products/bal.svg',
        'Ton Balığı Konservesi' => '/Küresel/assets/images/products/ton-baligi.svg',
        'Organik Pamuk Tişört' => '/Küresel/assets/images/products/tisort.svg',
        'Kot Pantolon' => '/Küresel/assets/images/products/kot-pantolon.svg',
        'Akıllı Telefon' => '/Küresel/assets/images/products/akilli-telefon.svg',
        'Dizüstü Bilgisayar' => '/Küresel/assets/images/products/dizustu-bilgisayar.svg',
        'Doğal Şampuan' => '/Küresel/assets/images/products/dogal-sampuan.svg',
        'Organik Krem' => '/Küresel/assets/images/products/organik-krem.svg'
    ];
    
    echo "Fixing image paths in database...\n";
    
    foreach ($productImages as $productName => $imagePath) {
        // Get the product to verify it exists
        $sql = "SELECT id FROM products WHERE product_name = ?";
        $product = $db->fetchRow($sql, [$productName]);
        
        if ($product) {
            // Update the product with the correct image path
            $sql = "UPDATE products SET product_images = ? WHERE id = ?";
            $db->execute($sql, [json_encode([$imagePath]), $product['id']]);
            echo "✓ Fixed image for: " . $productName . "\n";
        } else {
            echo "✗ Product not found: " . $productName . "\n";
        }
    }
    
    echo "\n✅ All product images have been fixed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>