<?php
// Direct database query to check image paths

// Define required constants
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');

// Include config
$config = require CONFIG_DIR . '/database.php';

try {
    $dbConfig = $config['database'];
    
    $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
    
    $pdo = new PDO(
        $dsn,
        $dbConfig['username'],
        $dbConfig['password'],
        $dbConfig['options']
    );
    
    $stmt = $pdo->query("SELECT id, product_name, product_images FROM products LIMIT 5");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Product images data:\n";
    foreach ($products as $product) {
        echo "ID: " . $product['id'] . "\n";
        echo "Name: " . $product['product_name'] . "\n";
        echo "Images (raw): " . $product['product_images'] . "\n";
        
        // Try to decode if it's JSON
        if ($product['product_images']) {
            $decoded = json_decode($product['product_images'], true);
            echo "Images (decoded): ";
            var_dump($decoded);
        }
        echo "---\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>