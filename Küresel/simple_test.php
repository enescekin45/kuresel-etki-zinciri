<?php
// Define required constants
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

// Include required files
require_once CONFIG_DIR . '/database.php';
require_once CLASSES_DIR . '/Database.php';
require_once CLASSES_DIR . '/Product.php';

try {
    $product = new Product();
    
    // Check for PRD-GIDA-002
    echo "Checking for product PRD-GIDA-002...\n";
    try {
        $product->loadByProductCode('PRD-GIDA-002');
        echo "Found: " . $product->getProductName() . " (Code: " . $product->getProductCode() . ", Barcode: " . $product->getBarcode() . ")\n";
    } catch (Exception $e) {
        echo "Not found: " . $e->getMessage() . "\n";
    }
    
    // Check for PRD-GIDA-003
    echo "\nChecking for product PRD-GIDA-003...\n";
    try {
        $product->loadByProductCode('PRD-GIDA-003');
        echo "Found: " . $product->getProductName() . " (Code: " . $product->getProductCode() . ", Barcode: " . $product->getBarcode() . ")\n";
    } catch (Exception $e) {
        echo "Not found: " . $e->getMessage() . "\n";
    }
    
    // Check for barcode 8691234567891
    echo "\nChecking for barcode 8691234567891...\n";
    try {
        $product->loadByBarcode('8691234567891');
        echo "Found: " . $product->getProductName() . " (Code: " . $product->getProductCode() . ", Barcode: " . $product->getBarcode() . ")\n";
    } catch (Exception $e) {
        echo "Not found: " . $e->getMessage() . "\n";
    }
    
    // Check for barcode 8691234567892
    echo "\nChecking for barcode 8691234567892...\n";
    try {
        $product->loadByBarcode('8691234567892');
        echo "Found: " . $product->getProductName() . " (Code: " . $product->getProductCode() . ", Barcode: " . $product->getBarcode() . ")\n";
    } catch (Exception $e) {
        echo "Not found: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>