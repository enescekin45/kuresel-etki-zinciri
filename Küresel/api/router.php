<?php
// API Router
// This file handles all API requests and routes them to the appropriate endpoint

// Get the request URI and method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Debug logging
error_log("Original URI: " . $_SERVER['REQUEST_URI']);
error_log("Parsed URI: " . $uri);
error_log("Method: " . $method);

// Remove project path first (handle both encoded and unencoded versions)
$uri = str_replace('/K%C3%BCresel', '', $uri);
error_log("After removing /K%C3%BCresel: " . $uri);

$uri = str_replace('/Küresel', '', $uri);
error_log("After removing /Küresel: " . $uri);

// Remove /api/v1 or just /api
$uri = preg_replace('#^/api/v\d+#', '', $uri);
error_log("After removing /api/vX: " . $uri);

// Route the request
switch ($uri) {
    // Admin API endpoints
    case '/admin/stats/users':
        require_once ROOT_DIR . '/api/v1/admin/stats/users.php';
        break;
        
    case '/admin/stats/companies':
        require_once ROOT_DIR . '/api/v1/admin/stats/companies.php';
        break;
        
    case '/admin/stats/validators':
        require_once ROOT_DIR . '/api/v1/admin/stats/validators.php';
        break;
        
    case '/admin/stats/products':
        require_once ROOT_DIR . '/api/v1/admin/stats/products.php';
        break;
        
    case '/admin/stats/overview':
        require_once ROOT_DIR . '/api/v1/admin/stats/overview.php';
        break;
        
    case (preg_match('/^\/admin\/users\/(\d+)$/', $uri, $matches) ? true : false):
        // Extract user ID from URI
        $_GET['id'] = $matches[1];
        require_once ROOT_DIR . '/api/v1/admin/users/get.php';
        break;
    
    // Auth endpoints
    case '/auth/2fa-setup':
        require_once ROOT_DIR . '/api/v1/auth/2fa-setup.php';
        break;
        
    case '/auth/devices':
        require_once ROOT_DIR . '/api/v1/auth/devices.php';
        break;
        
    case '/auth/login':
        require_once ROOT_DIR . '/api/v1/auth/login.php';
        break;
        
    case '/auth/logout':
        require_once ROOT_DIR . '/api/v1/auth/logout.php';
        break;
        
    case '/auth/notification-preferences':
        require_once ROOT_DIR . '/api/v1/auth/notification-preferences.php';
        break;
        
    case '/auth/profile':
        require_once ROOT_DIR . '/api/v1/auth/profile.php';
        break;
        
    case '/auth/register':
        require_once ROOT_DIR . '/api/v1/auth/register.php';
        break;
        
    case '/auth/remove-profile-image':
        require_once ROOT_DIR . '/api/v1/auth/remove-profile-image.php';
        break;
        
    case '/auth/scheduled-notifications':
        require_once ROOT_DIR . '/api/v1/auth/scheduled-notifications.php';
        break;
        
    case '/auth/upload-profile-image':
        require_once ROOT_DIR . '/api/v1/auth/upload-profile-image.php';
        break;
    
    // Company endpoints
    case '/company/products/recent':
        require_once ROOT_DIR . '/api/v1/company/products/recent.php';
        break;
        
    case '/company/stats/pending':
        require_once ROOT_DIR . '/api/v1/company/stats/pending.php';
        break;
        
    case '/company/stats/products':
        require_once ROOT_DIR . '/api/v1/company/stats/products.php';
        break;
        
    case '/company/stats/scans':
        require_once ROOT_DIR . '/api/v1/company/stats/scans.php';
        break;
        
    case '/company/stats/validation':
        require_once ROOT_DIR . '/api/v1/company/stats/validation.php';
        break;
        
    case '/company/environmental-impact':
        require_once ROOT_DIR . '/api/v1/company/environmental-impact.php';
        break;
        
    case '/company/social-impact':
        require_once ROOT_DIR . '/api/v1/company/social-impact.php';
        break;
        
    case '/company/update':
        require_once ROOT_DIR . '/api/v1/company/update.php';
        break;
    
    // Products endpoints
    case '/products/get':
        require_once ROOT_DIR . '/api/v1/products/get.php';
        break;
        
    case '/products/list':
        require_once ROOT_DIR . '/api/v1/products/list.php';
        break;
        
    case '/products/upload-image':
        require_once ROOT_DIR . '/api/v1/products/upload-image.php';
        break;
    
    // Validator endpoints
    case '/validator/activities/recent':
        require_once ROOT_DIR . '/api/v1/validator/activities/recent.php';
        break;
        
    case '/validator/stats/approved':
        require_once ROOT_DIR . '/api/v1/validator/stats/approved.php';
        break;
        
    case '/validator/stats/pending':
        require_once ROOT_DIR . '/api/v1/validator/stats/pending.php';
        break;
        
    case '/validator/stats/rejected':
        require_once ROOT_DIR . '/api/v1/validator/stats/rejected.php';
        break;
        
    case '/validator/stats/total':
        require_once ROOT_DIR . '/api/v1/validator/stats/total.php';
        break;
        
    case '/validator/validations/pending':
        require_once ROOT_DIR . '/api/v1/validator/validations/pending.php';
        break;
        
    case '/validator/performance':
        require_once ROOT_DIR . '/api/v1/validator/performance.php';
        break;
        
    default:
        // Debug logging for unmatched routes
        error_log("Unmatched route: " . $uri);
        // Return 404 for unmatched routes
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Endpoint not found']);
        break;
}