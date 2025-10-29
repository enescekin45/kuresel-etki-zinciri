<?php
// Check impact data for current company
session_start();

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

if (!defined('FRONTEND_DIR')) {
    define('FRONTEND_DIR', ROOT_DIR . '/frontend');
}

require_once CONFIG_DIR . '/database.php';
require_once CLASSES_DIR . '/Database.php';
require_once CLASSES_DIR . '/Auth.php';
require_once CLASSES_DIR . '/Company.php';

try {
    $auth = Auth::getInstance();
    
    if (!$auth->isLoggedIn()) {
        echo "User not logged in";
        exit;
    }
    
    // Get current user
    $user = $auth->getCurrentUser();
    echo "<h2>Current User:</h2>";
    echo "<pre>";
    print_r($user->toArray());
    echo "</pre>";
    
    // Load company
    $company = new Company();
    $company->loadByUserId($user->getId());
    
    echo "<h2>Current Company:</h2>";
    echo "<pre>";
    print_r($company->toArray());
    echo "</pre>";
    
    // Get environmental impact data for this company
    $db = Database::getInstance();
    
    // Get average environmental scores for company's products
    $sql = "SELECT 
                AVG(isc.environmental_score) as avg_environmental_score,
                AVG(isc.carbon_footprint_score) as avg_carbon_footprint,
                AVG(isc.water_footprint_score) as avg_water_footprint,
                AVG(isc.biodiversity_impact_score) as avg_biodiversity_impact,
                AVG(isc.waste_score) as avg_waste_score
            FROM impact_scores isc
            JOIN product_batches pb ON isc.product_batch_id = pb.id
            JOIN products p ON pb.product_id = p.id
            WHERE p.company_id = ?";
    
    $result = $db->fetchRow($sql, [$company->getId()]);
    
    echo "<h2>Environmental Impact Data for Company ID " . $company->getId() . ":</h2>";
    echo "<pre>";
    print_r($result);
    echo "</pre>";
    
    // Get social impact data for this company
    $sql = "SELECT 
                AVG(isc.social_score) as avg_social_score,
                AVG(isc.fair_wages_score) as avg_fair_wages,
                AVG(isc.working_conditions_score) as avg_working_conditions,
                AVG(isc.community_impact_score) as avg_community_impact,
                AVG(isc.labor_rights_score) as avg_labor_rights
            FROM impact_scores isc
            JOIN product_batches pb ON isc.product_batch_id = pb.id
            JOIN products p ON pb.product_id = p.id
            WHERE p.company_id = ?";
    
    $result = $db->fetchRow($sql, [$company->getId()]);
    
    echo "<h2>Social Impact Data for Company ID " . $company->getId() . ":</h2>";
    echo "<pre>";
    print_r($result);
    echo "</pre>";
    
    // Check products for this company
    $sql = "SELECT 
                p.id,
                p.product_name,
                pb.id as batch_id,
                isc.id as impact_score_id,
                isc.environmental_score,
                isc.social_score
            FROM products p
            LEFT JOIN product_batches pb ON p.id = pb.product_id
            LEFT JOIN impact_scores isc ON pb.id = isc.product_batch_id
            WHERE p.company_id = ?
            LIMIT 10";
    
    $products = $db->fetchAll($sql, [$company->getId()]);
    
    echo "<h2>Products for Company ID " . $company->getId() . ":</h2>";
    echo "<pre>";
    print_r($products);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    echo "<pre>";
    echo $e->getTraceAsString();
    echo "</pre>";
}
?>