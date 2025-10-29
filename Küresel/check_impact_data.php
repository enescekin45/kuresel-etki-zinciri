<?php
// Check impact data in database

// Define required constants only if not already defined
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

try {
    $db = Database::getInstance();
    
    // Check if there are any products
    $sql = "SELECT id, product_name, company_id FROM products LIMIT 5";
    $products = $db->fetchAll($sql);
    
    echo "<h2>Products in Database:</h2>";
    echo "<pre>";
    print_r($products);
    echo "</pre>";
    
    // Check if there are any impact scores
    $sql = "SELECT * FROM impact_scores LIMIT 5";
    $impactScores = $db->fetchAll($sql);
    
    echo "<h2>Impact Scores in Database:</h2>";
    echo "<pre>";
    print_r($impactScores);
    echo "</pre>";
    
    // Check if there are any product batches
    $sql = "SELECT * FROM product_batches LIMIT 5";
    $batches = $db->fetchAll($sql);
    
    echo "<h2>Product Batches in Database:</h2>";
    echo "<pre>";
    print_r($batches);
    echo "</pre>";
    
    // Check the relationship between products, batches and impact scores
    $sql = "SELECT 
                p.id as product_id,
                p.product_name,
                pb.id as batch_id,
                isc.id as impact_score_id,
                isc.environmental_score,
                isc.social_score
            FROM products p
            LEFT JOIN product_batches pb ON p.id = pb.product_id
            LEFT JOIN impact_scores isc ON pb.id = isc.product_batch_id
            LIMIT 10";
    
    $relationships = $db->fetchAll($sql);
    
    echo "<h2>Product Relationships:</h2>";
    echo "<pre>";
    print_r($relationships);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>