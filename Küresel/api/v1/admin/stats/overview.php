<?php
/**
 * Admin Statistics Overview API Endpoint
 * Provides time-based statistics for dashboard charts
 */

// Define required constants only if not already defined
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', realpath(__DIR__ . '/../../../../..'));
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

try {
    // Get the Auth instance
    $auth = Auth::getInstance();
    
    // Check if user is logged in
    if (!$auth->isLoggedIn()) {
        throw new Exception('User not logged in', 401);
    }
    
    // Check if user is an admin
    if (!$auth->isAdmin()) {
        throw new Exception('Access denied. Admin access required.', 403);
    }
    
    // Get database instance
    $db = Database::getInstance();
    
    // Get time range from query parameters (default to last 30 days)
    $days = isset($_GET['days']) ? (int)$_GET['days'] : 30;
    $days = max(7, min(365, $days)); // Limit between 7 and 365 days
    
    // Get user registration statistics over time
    $userStatsSql = "
        SELECT 
            DATE(created_at) as date,
            COUNT(*) as count,
            user_type
        FROM users 
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
        GROUP BY DATE(created_at), user_type
        ORDER BY date ASC
    ";
    
    $userStats = $db->fetchAll($userStatsSql, [$days]);
    
    // Organize user stats by date and type
    $userChartData = [];
    $dates = [];
    $userTypes = [];
    
    // Initialize data structure
    foreach ($userStats as $stat) {
        $date = $stat['date'];
        $type = $stat['user_type'];
        
        if (!in_array($date, $dates)) {
            $dates[] = $date;
        }
        
        if (!in_array($type, $userTypes)) {
            $userTypes[] = $type;
        }
        
        if (!isset($userChartData[$date])) {
            $userChartData[$date] = [];
        }
        
        $userChartData[$date][$type] = (int)$stat['count'];
    }
    
    // Fill in missing dates with zero values
    $startDate = new DateTime("-{$days} days");
    $endDate = new DateTime();
    $interval = new DateInterval('P1D');
    $dateRange = new DatePeriod($startDate, $interval, $endDate);
    
    foreach ($dateRange as $date) {
        $dateStr = $date->format('Y-m-d');
        if (!in_array($dateStr, $dates)) {
            $dates[] = $dateStr;
            $userChartData[$dateStr] = [];
        }
    }
    
    sort($dates);
    
    // Prepare user chart data for Chart.js
    $userChartLabels = $dates;
    $userChartDatasets = [];
    
    foreach ($userTypes as $type) {
        $data = [];
        foreach ($dates as $date) {
            $data[] = isset($userChartData[$date][$type]) ? $userChartData[$date][$type] : 0;
        }
        
        $userChartDatasets[] = [
            'label' => ucfirst($type),
            'data' => $data,
            'borderWidth' => 2
        ];
    }
    
    // Get company statistics by type
    $companyStatsSql = "
        SELECT 
            company_type,
            COUNT(*) as count
        FROM companies 
        GROUP BY company_type
        ORDER BY count DESC
    ";
    
    $companyStats = $db->fetchAll($companyStatsSql);
    
    // Get product statistics by category
    $productStatsSql = "
        SELECT 
            category,
            COUNT(*) as count
        FROM products 
        WHERE category IS NOT NULL AND category != ''
        GROUP BY category
        ORDER BY count DESC
        LIMIT 10
    ";
    
    $productStats = $db->fetchAll($productStatsSql);
    
    // Get validation statistics
    $validationStatsSql = "
        SELECT 
            validation_result,
            COUNT(*) as count
        FROM validation_records 
        GROUP BY validation_result
        ORDER BY count DESC
    ";
    
    $validationStats = $db->fetchAll($validationStatsSql);
    
    // Return success response
    $response = [
        'success' => true,
        'data' => [
            'user_registration' => [
                'labels' => $userChartLabels,
                'datasets' => $userChartDatasets
            ],
            'companies_by_type' => $companyStats,
            'products_by_category' => $productStats,
            'validations_by_result' => $validationStats
        ]
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