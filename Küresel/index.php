<?php
/**
 * Global Impact Chain - Main Entry Point
 * 
 * This file serves as the main entry point for the Küresel Etki Zinciri platform.
 * It handles routing, initialization, and basic authentication.
 */

// Start session for user management
session_start();

// Set error reporting for development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define project root directory
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');
define('API_DIR', ROOT_DIR . '/api');
define('FRONTEND_DIR', ROOT_DIR . '/frontend');
define('BLOCKCHAIN_DIR', ROOT_DIR . '/blockchain');
define('UPLOADS_DIR', ROOT_DIR . '/uploads');
define('QR_CODES_DIR', ROOT_DIR . '/qr-codes');

// Autoloader for classes
spl_autoload_register(function ($class_name) {
    $file = CLASSES_DIR . '/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Include configuration files
require_once CONFIG_DIR . '/database.php';
require_once CONFIG_DIR . '/blockchain.php';
require_once CONFIG_DIR . '/app.php';

// Basic routing - Check for query parameter first, then URL path
$page = isset($_GET['page']) ? $_GET['page'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;

if (!$page) {
    // Try URL-based routing
    $request_uri = $_SERVER['REQUEST_URI'];
    $path = parse_url($request_uri, PHP_URL_PATH);
    
    // Handle both encoded and non-encoded paths
    $decoded_path = urldecode($path);
    $path = str_replace(['/Küresel', '/K%C3%BCresel'], '', $decoded_path); // Remove base directory (both encoded and decoded)
    
    // Convert path to page parameter
    if ($path === '/' || $path === '' || $path === '/index.php') {
        $page = 'home';
    } elseif ($path === '/product') {
        $page = 'product';
    } elseif ($path === '/about') {
        $page = 'about';
    } elseif ($path === '/contact') {
        $page = 'contact';
    } elseif ($path === '/login') {
        $page = 'login';
    } elseif ($path === '/register') {
        $page = 'register';
    } elseif ($path === '/forgot-password') {
        $page = 'forgot-password';
    } elseif ($path === '/admin') {
        $page = 'admin';
    } elseif ($path === '/company') {
        $page = 'company';
    } elseif ($path === '/validator') {
        $page = 'validator';
    } elseif ($path === '/consumer') {
        $page = 'consumer';
    } elseif ($path === '/team') {
        $page = 'team';
    } elseif ($path === '/careers') {
        $page = 'careers';
    } elseif ($path === '/press') {
        $page = 'press';
    } elseif ($path === '/help') {
        $page = 'help';
    } elseif ($path === '/docs') {
        $page = 'docs';
    } elseif ($path === '/api-docs') {
        $page = 'api-docs';
    } elseif ($path === '/privacy') {
        $page = 'privacy';
    } elseif ($path === '/terms') {
        $page = 'terms';
    } elseif ($path === '/cookies') {
        $page = 'cookies';
    } elseif ($path === '/profile') {
        $page = 'profile';
    } elseif ($path === '/settings') {
        $page = 'settings';
    } elseif ($path === '/change-password') {
        $page = 'change-password';
    } elseif ($path === '/test-notification-sending') {
        $page = 'test-notification-sending';
    } elseif ($path === '/send-notification-example') {
        $page = 'send-notification-example';
    } elseif ($path === '/final-notification-test') {
        $page = 'final-notification-test';
    } elseif ($path === '/test-scheduled-notifications') {
        $page = 'test-scheduled-notifications';
    }
}

// Handle API requests
if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
    // Route to API handler
    require_once API_DIR . '/router.php';
    exit;
}

// Handle QR code requests
if (strpos($_SERVER['REQUEST_URI'], '/qr/') !== false) {
    // Route to QR code handler
    require_once 'qr_handler.php';
    exit;
}

// Handle frontend requests based on page parameter
if ($page === 'home' || $page === null) {
    // Home page
    require_once FRONTEND_DIR . '/pages/home.php';
} elseif ($page === 'product') {
    // Product pages
    if ($action === 'add') {
        // Add product page
        require_once FRONTEND_DIR . '/pages/product/add.php';
    } elseif ($action === 'list') {
        // List products page
        require_once FRONTEND_DIR . '/pages/product/list.php';
    } elseif ($action === 'edit') {
        // Edit product page
        require_once FRONTEND_DIR . '/pages/product/edit.php';
    } elseif ($action === 'view') {
        // View product page
        require_once FRONTEND_DIR . '/pages/product/view.php';
    } else {
        // Product profile page
        require_once FRONTEND_DIR . '/pages/product.php';
    }
} elseif ($page === 'about') {
    // About page
    require_once FRONTEND_DIR . '/pages/about.php';
} elseif ($page === 'contact') {
    // Contact page
    require_once FRONTEND_DIR . '/pages/contact.php';
} elseif ($page === 'login') {
    // Login page
    require_once FRONTEND_DIR . '/pages/login.php';
} elseif ($page === 'register') {
    // Register page
    require_once FRONTEND_DIR . '/pages/register.php';
} elseif ($page === 'forgot-password') {
    // Forgot password page
    require_once FRONTEND_DIR . '/pages/forgot-password.php';
} elseif ($page === 'admin') {
    // Admin dashboard
    require_once FRONTEND_DIR . '/pages/admin.php';
} elseif ($page === 'company') {
    // Company dashboard
    require_once FRONTEND_DIR . '/pages/company.php';
} elseif ($page === 'validator') {
    // Validator dashboard
    require_once FRONTEND_DIR . '/pages/validator.php';
} elseif ($page === 'consumer') {
    // Consumer dashboard
    require_once FRONTEND_DIR . '/pages/consumer.php';
} elseif ($page === 'team') {
    // Team page
    require_once FRONTEND_DIR . '/pages/team.php';
} elseif ($page === 'careers') {
    // Careers page
    require_once FRONTEND_DIR . '/pages/careers.php';
} elseif ($page === 'press') {
    // Press page
    require_once FRONTEND_DIR . '/pages/press.php';
} elseif ($page === 'help') {
    // Help page
    require_once FRONTEND_DIR . '/pages/help.php';
} elseif ($page === 'docs') {
    // Documentation page
    require_once FRONTEND_DIR . '/pages/docs.php';
} elseif ($page === 'api-docs') {
    // API Documentation page
    require_once FRONTEND_DIR . '/pages/api-docs.php';
} elseif ($page === 'privacy') {
    // Privacy policy page
    require_once FRONTEND_DIR . '/pages/privacy.php';
} elseif ($page === 'terms') {
    // Terms of service page
    require_once FRONTEND_DIR . '/pages/terms.php';
} elseif ($page === 'cookies') {
    // Cookies policy page
    require_once FRONTEND_DIR . '/pages/cookies.php';
} elseif ($page === 'profile') {
    // User profile page
    require_once FRONTEND_DIR . '/pages/profile.php';
} elseif ($page === 'settings') {
    // User settings page
    require_once FRONTEND_DIR . '/pages/settings.php';
} elseif ($page === 'change-password') {
    // Change password page
    require_once FRONTEND_DIR . '/pages/change-password.php';
} elseif ($page === 'test-notification-sending') {
    // Notification sending test page
    require_once 'test_notification_sending.php';
} elseif ($page === 'send-notification-example') {
    // Notification sending example page
    require_once 'send_notification_example.php';
} elseif ($page === 'final-notification-test') {
    // Final notification test page
    require_once 'final_notification_test.php';
} elseif ($page === 'test-scheduled-notifications') {
    // Scheduled notifications test page
    require_once 'test_scheduled_notifications.php';
} elseif ($page === 'test-design') {
    // Design test page
    require_once FRONTEND_DIR . '/pages/test-design.php';
} elseif ($page === 'validation') {
    // Validation pages
    if ($action === 'pending') {
        // Pending validations page
        require_once FRONTEND_DIR . '/pages/validation/pending.php';
    } elseif ($action === 'all') {
        // All validations page
        require_once FRONTEND_DIR . '/pages/validation/all.php';
    } else {
        // Default validation page or 404
        http_response_code(404);
        require_once FRONTEND_DIR . '/pages/404.php';
    }
} elseif ($page === 'validator' && $action === 'profile') {
    // Validator profile editing page
    require_once FRONTEND_DIR . '/pages/validator/profile.php';
} elseif ($page === 'validator' && $action === 'reports') {
    // Validator reports page
    require_once FRONTEND_DIR . '/pages/validator/reports.php';
}
else {
    // 404 Not Found
    http_response_code(404);
    require_once FRONTEND_DIR . '/pages/404.php';
}
?>