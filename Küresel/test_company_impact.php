<?php
// Test company impact data
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

require_once CONFIG_DIR . '/database.php';
require_once CLASSES_DIR . '/Database.php';
require_once CLASSES_DIR . '/Auth.php';
require_once CLASSES_DIR . '/Company.php';

try {
    $auth = Auth::getInstance();
    
    if (!$auth->isLoggedIn()) {
        echo "User not logged in. Please log in first.";
        exit;
    }
    
    echo "<h2>Authentication Status</h2>";
    echo "<p>Logged in: " . ($auth->isLoggedIn() ? "Yes" : "No") . "</p>";
    echo "<p>Is company: " . ($auth->isCompany() ? "Yes" : "No") . "</p>";
    
    if (!$auth->isCompany()) {
        echo "<p>User is not a company. Only company users can view impact data.</p>";
        exit;
    }
    
    // Get current user
    $user = $auth->getCurrentUser();
    echo "<h2>Current User</h2>";
    echo "<p>User ID: " . $user->getId() . "</p>";
    echo "<p>User Email: " . $user->getEmail() . "</p>";
    echo "<p>User Type: " . $user->getUserType() . "</p>";
    
    // Load company
    $company = new Company();
    $company->loadByUserId($user->getId());
    
    echo "<h2>Current Company</h2>";
    echo "<p>Company ID: " . $company->getId() . "</p>";
    echo "<p>Company Name: " . $company->getCompanyName() . "</p>";
    
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
    
    echo "<h2>Environmental Impact Data for Company ID " . $company->getId() . "</h2>";
    echo "<pre>";
    print_r($result);
    echo "</pre>";
    
    // Check if we have any data
    if (!$result || ($result['avg_environmental_score'] === null && $result['avg_carbon_footprint'] === null)) {
        echo "<p><strong>No environmental impact data found for this company.</strong></p>";
        
        // Let's check what products this company has
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
                ORDER BY p.id";
        
        $products = $db->fetchAll($sql, [$company->getId()]);
        
        echo "<h3>Company Products</h3>";
        echo "<pre>";
        print_r($products);
        echo "</pre>";
    } else {
        echo "<p><strong>Environmental impact data found!</strong></p>";
    }
    
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
    
    echo "<h2>Social Impact Data for Company ID " . $company->getId() . "</h2>";
    echo "<pre>";
    print_r($result);
    echo "</pre>";
    
    // Check if we have any data
    if (!$result || ($result['avg_social_score'] === null && $result['avg_fair_wages'] === null)) {
        echo "<p><strong>No social impact data found for this company.</strong></p>";
    } else {
        echo "<p><strong>Social impact data found!</strong></p>";
    }
    
} catch (Exception $e) {
    echo "<h2>Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>";
    echo $e->getTraceAsString();
    echo "</pre>";
}
?>