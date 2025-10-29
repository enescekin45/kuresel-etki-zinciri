<?php
/**
 * Validator Recent Activities API Endpoint
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
require_once CLASSES_DIR . '/Validator.php';

try {
    // Get the Auth instance
    $auth = Auth::getInstance();
    
    // Check if user is logged in
    if (!$auth->isLoggedIn()) {
        throw new Exception('User not logged in', 401);
    }
    
    // Check if user is a validator
    if (!$auth->isValidator()) {
        throw new Exception('Access denied. Validator access required.', 403);
    }
    
    // Get current user
    $user = $auth->getCurrentUser();
    
    // Load validator
    $validator = new Validator();
    $validator->loadByUserId($user->getId());
    
    // Get database instance
    $db = Database::getInstance();
    
    // Get recent activities (last 5 activities)
    $sql = "SELECT vr.*, p.product_name, c.company_name
            FROM validation_records vr
            JOIN supply_chain_steps scs ON vr.supply_chain_step_id = scs.id
            JOIN product_batches pb ON scs.product_batch_id = pb.id
            JOIN products p ON pb.product_id = p.id
            JOIN companies c ON scs.company_id = c.id
            WHERE vr.validator_id = ?
            ORDER BY vr.completed_at DESC, vr.requested_at DESC
            LIMIT 5";
    
    $activities = $db->fetchAll($sql, [$validator->getId()]);
    
    // Format the data for the frontend
    $formattedActivities = [];
    foreach ($activities as $activity) {
        $formattedActivities[] = [
            'id' => $activity['id'],
            'product_name' => $activity['product_name'],
            'company_name' => $activity['company_name'],
            'action' => $activity['validation_result'] ? 
                ($activity['validation_result'] === 'approved' ? 'Doğrulama Onaylandı' : 'Doğrulama Reddedildi') : 
                'Doğrulama Talebi Alındı',
            'date' => $activity['completed_at'] ?? $activity['requested_at'],
            'status' => $activity['validation_result'] ?? 'pending'
        ];
    }
    
    // Return success response
    $response = [
        'success' => true,
        'data' => $formattedActivities
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