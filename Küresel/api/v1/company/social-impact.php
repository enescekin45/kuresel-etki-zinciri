<?php
/**
 * Company Social Impact API Endpoint
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
    error_log("Social Impact API called by user ID: " . $user->getId());
    
    // Load company
    $company = new Company();
    $company->loadByUserId($user->getId());
    
    error_log("Loaded company ID: " . $company->getId() . " Name: " . $company->getCompanyName());
    
    // Get social impact data
    $db = Database::getInstance();
    
    // Get average social scores for company's products
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
    
    // Log result for debugging
    error_log("Social query result: " . json_encode($result));
    
    // Check if we have any data
    if (!$result || ($result['avg_social_score'] === null && $result['avg_fair_wages'] === null)) {
        error_log("No social data found for company ID: " . $company->getId());
        $socialData = null;
    } else {
        error_log("Social data found for company ID: " . $company->getId());
        $socialData = [
            'social_score' => round($result['avg_social_score'] ?? 0, 2),
            'fair_wages' => round($result['avg_fair_wages'] ?? 0, 2),
            'working_conditions' => round($result['avg_working_conditions'] ?? 0, 2),
            'community_impact' => round($result['avg_community_impact'] ?? 0, 2),
            'labor_rights' => round($result['avg_labor_rights'] ?? 0, 2)
        ];
    }
    
    // Return success response
    $response = [
        'success' => true,
        'data' => $socialData
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