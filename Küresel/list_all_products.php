<?php
// List all products with their IDs and UUIDs

// Define required constants
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

// Autoloader for classes
spl_autoload_register(function ($class_name) {
    $file = CLASSES_DIR . '/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $stmt = $pdo->query("SELECT id, uuid, product_name, product_images FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "All products:\n";
    foreach ($products as $product) {
        echo "ID: " . $product['id'] . "\n";
        echo "UUID: " . $product['uuid'] . "\n";
        echo "Name: " . $product['product_name'] . "\n";
        echo "Images: " . $product['product_images'] . "\n";
        echo "---\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>