<?php
// Define required constants
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', __DIR__);
}

if (!defined('CONFIG_DIR')) {
    define('CONFIG_DIR', ROOT_DIR . '/config');
}

if (!defined('CLASSES_DIR')) {
    define('CLASSES_DIR', ROOT_DIR . '/classes');
}

require_once CONFIG_DIR . '/database.php';
require_once CLASSES_DIR . '/Database.php';

$db = Database::getInstance();

// Check if there are any products in the database
$sql = "SELECT * FROM products LIMIT 5";
$products = $db->fetchAll($sql);

echo "Products in database:\n";
echo "=====================\n";
if (empty($products)) {
    echo "No products found in database\n";
} else {
    foreach ($products as $product) {
        echo "ID: " . $product['id'] . "\n";
        echo "Name: " . $product['product_name'] . "\n";
        echo "Company ID: " . $product['company_id'] . "\n";
        echo "Status: " . $product['status'] . "\n";
        echo "Created: " . $product['created_at'] . "\n";
        echo "Images: " . $product['product_images'] . "\n";
        echo "-------------------\n";
    }
}

// Check if there are any companies
$sql = "SELECT * FROM companies LIMIT 5";
$companies = $db->fetchAll($sql);

echo "\nCompanies in database:\n";
echo "=====================\n";
if (empty($companies)) {
    echo "No companies found in database\n";
} else {
    foreach ($companies as $company) {
        echo "ID: " . $company['id'] . "\n";
        echo "Name: " . $company['company_name'] . "\n";
        echo "User ID: " . $company['user_id'] . "\n";
        echo "-------------------\n";
    }
}
?>