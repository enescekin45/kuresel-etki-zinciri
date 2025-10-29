<?php
/**
 * Company Environmental Impact API Endpoint
 */

// Define required constants only if not already defined
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', __DIR__ . '/../../../..');
}

if (!defined('CONFIG_DIR')) {
    define('CONFIG_DIR', ROOT_DIR . '/config');
}

if (!defined('CLASSES_DIR')) {
    define('CLASSES_DIR', ROOT_DIR . '/classes');
}

// Include required files
require_once CONFIG_DIR . '/database.php';
require_once CLASSES_DIR . '/Database.php';
require_once CLASSES_DIR . '/Auth.php';
require_once CLASSES_DIR . '/Company.php';

try {
    // Get the Auth instance
    $auth = Auth::getInstance();
    
    // Check if user is logged in
    if (!$auth->isLoggedIn()) {
        throw new Exception('User not logged in', 401);
    }
    
    // Check if user is a company
    if (!$auth->isCompany()) {
        throw new Exception('Access denied. Company access required.', 403);
    }
    
    // Get current user
    $user = $auth->getCurrentUser();
    
    // Log for debugging
    error_log("Environmental Impact API called by user ID: " . $user->getId());
    
    // Load company
    $company = new Company();
    $company->loadByUserId($user->getId());
    
    error_log("Loaded company ID: " . $company->getId() . " Name: " . $company->getCompanyName());
    
    // Get environmental impact data
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
    
    // Log result for debugging
    error_log("Environmental query result: " . json_encode($result));
    
    // Check if we have any data
    if (!$result || ($result['avg_environmental_score'] === null && $result['avg_carbon_footprint'] === null)) {
        error_log("No environmental data found for company ID: " . $company->getId());
        $environmentalData = null;
    } else {
        error_log("Environmental data found for company ID: " . $company->getId());
        $environmentalData = [
            'environmental_score' => round($result['avg_environmental_score'] ?? 0, 2),
            'carbon_footprint' => round($result['avg_carbon_footprint'] ?? 0, 2),
            'water_footprint' => round($result['avg_water_footprint'] ?? 0, 2),
            'biodiversity_impact' => round($result['avg_biodiversity_impact'] ?? 0, 2),
            'waste_score' => round($result['avg_waste_score'] ?? 0, 2)
        ];
    }
    
    // Return success response
    $response = [
        'success' => true,
        'data' => $environmentalData
    ];
    
} catch (Exception $e) {
    $statusCode = $e->getCode() ?: 400;
    http_response_code($statusCode);
    
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => $statusCode
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>