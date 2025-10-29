<?php
/**
 * Company Update API Endpoint
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

header('Content-Type: application/json');

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
    
    // Load company
    $company = new Company();
    $company->loadByUserId($user->getId());
    
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Invalid JSON data', 400);
    }
    
    // Prepare update data
    $updateData = [];
    
    // Handle company name
    if (isset($input['company_name']) && !empty($input['company_name'])) {
        $updateData['company_name'] = trim($input['company_name']);
    }
    
    // Handle industry sector
    if (isset($input['industry_sector'])) {
        $updateData['industry_sector'] = trim($input['industry_sector']);
    }
    
    // Handle address fields
    if (isset($input['address_line1'])) {
        $updateData['address_line1'] = trim($input['address_line1']);
    }
    
    if (isset($input['address_line2'])) {
        $updateData['address_line2'] = trim($input['address_line2']);
    }
    
    if (isset($input['city'])) {
        $updateData['city'] = trim($input['city']);
    }
    
    if (isset($input['postal_code'])) {
        $updateData['postal_code'] = trim($input['postal_code']);
    }
    
    if (isset($input['country'])) {
        $updateData['country'] = trim($input['country']);
    }
    
    // Update company if there's data to update
    if (!empty($updateData)) {
        // Add updated_at timestamp
        $updateData['updated_at'] = date('Y-m-d H:i:s');
        
        // Build SQL query correctly
        $fields = array_keys($updateData);
        $placeholders = array_fill(0, count($fields), '?');
        $values = array_values($updateData);
        
        $setClause = "";
        foreach ($fields as $field) {
            if (!empty($setClause)) {
                $setClause .= ", ";
            }
            $setClause .= "{$field} = ?";
        }
        
        $sql = "UPDATE companies SET {$setClause} WHERE id = ?";
        
        // Add company ID to values
        $values[] = $company->getId();
        
        // Execute update
        $db = Database::getInstance();
        $db->execute($sql, $values);
        
        // Reload company data
        $company->loadById($company->getId());
        
        $response = [
            'success' => true,
            'message' => 'Şirket bilgileri başarıyla güncellendi',
            'data' => $company->toArray(true)
        ];
    } else {
        $response = [
            'success' => true,
            'message' => 'Güncellenecek veri bulunamadı'
        ];
    }
    
} catch (Exception $e) {
    $statusCode = $e->getCode() ?: 400;
    http_response_code($statusCode);
    
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => $statusCode
    ];
}

echo json_encode($response);
?>