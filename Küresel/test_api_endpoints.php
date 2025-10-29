<?php
// Define required constants
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

// Include required classes
require_once CLASSES_DIR . '/User.php';
require_once CLASSES_DIR . '/Auth.php';
require_once CLASSES_DIR . '/Database.php';
require_once CLASSES_DIR . '/Validator.php';

// Start session
session_start();

// Get the actual test validator user ID
require_once CONFIG_DIR . '/database.php';
$db = Database::getInstance();
$user = new User();
$user->loadByEmail('test@validator.com');
$actualUserId = $user->getId();

// Simulate a logged-in validator user
$_SESSION['user_id'] = $actualUserId; // Our test validator user
$_SESSION['user_type'] = 'validator';
$_SESSION['logged_in'] = true;

// Create a mock Auth instance
class MockAuth {
    private $userId;
    private $userType;
    
    public function __construct($userId, $userType) {
        $this->userId = $userId;
        $this->userType = $userType;
    }
    
    public function isLoggedIn() {
        return true;
    }
    
    public function isValidator() {
        return $this->userType === 'validator';
    }
    
    public function getCurrentUser() {
        $user = new User();
        $user->loadById($this->userId);
        return $user;
    }
}

// Override the Auth class instance
$GLOBALS['mockAuth'] = new MockAuth($actualUserId, 'validator');

// Test the total validations endpoint
echo "Testing Total Validations Endpoint:\n";
ob_start();
include 'api/v1/validator/stats/total.php';
$output = ob_get_clean();
echo $output . "\n";

// Test the approved validations endpoint
echo "Testing Approved Validations Endpoint:\n";
ob_start();
include 'api/v1/validator/stats/approved.php';
$output = ob_get_clean();
echo $output . "\n";

// Test the rejected validations endpoint
echo "Testing Rejected Validations Endpoint:\n";
ob_start();
include 'api/v1/validator/stats/rejected.php';
$output = ob_get_clean();
echo $output . "\n";

// Test the pending validations endpoint
echo "Testing Pending Validations Endpoint:\n";
ob_start();
include 'api/v1/validator/stats/pending.php';
$output = ob_get_clean();
echo $output . "\n";

// Test the pending validations list endpoint
echo "Testing Pending Validations List Endpoint:\n";
ob_start();
include 'api/v1/validator/validations/pending.php';
$output = ob_get_clean();
echo $output . "\n";

// Test the recent activities endpoint
echo "Testing Recent Activities Endpoint:\n";
ob_start();
include 'api/v1/validator/activities/recent.php';
$output = ob_get_clean();
echo $output . "\n";

// Test the performance metrics endpoint
echo "Testing Performance Metrics Endpoint:\n";
ob_start();
include 'api/v1/validator/performance.php';
$output = ob_get_clean();
echo $output . "\n";
?>