<?php
// Comprehensive API test
echo "<h1>Comprehensive API Test</h1>";

// Start session
session_start();

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define constants
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

echo "<h2>1. Testing File Inclusion</h2>";

// Test if required files exist
$requiredFiles = [
    CONFIG_DIR . '/database.php',
    CONFIG_DIR . '/app.php',
    CLASSES_DIR . '/Database.php',
    CLASSES_DIR . '/Auth.php',
    CLASSES_DIR . '/User.php',
    CLASSES_DIR . '/UserPreferences.php'
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "<p>✅ File exists: " . basename($file) . "</p>";
    } else {
        echo "<p>❌ File missing: " . $file . "</p>";
    }
}

echo "<h2>2. Testing Database Connection</h2>";

try {
    require_once CONFIG_DIR . '/database.php';
    require_once CLASSES_DIR . '/Database.php';
    
    $db = Database::getInstance();
    echo "<p>✅ Database connection successful</p>";
    
    // Test a simple query
    $result = $db->fetchRow("SELECT 1 as test");
    if ($result && isset($result['test'])) {
        echo "<p>✅ Database query successful</p>";
    } else {
        echo "<p>❌ Database query failed</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Database connection error: " . $e->getMessage() . "</p>";
}

echo "<h2>3. Testing Auth System</h2>";

try {
    require_once CLASSES_DIR . '/Auth.php';
    require_once CLASSES_DIR . '/User.php';
    require_once CLASSES_DIR . '/UserPreferences.php';
    
    $auth = Auth::getInstance();
    echo "<p>✅ Auth instance created</p>";
    
    if ($auth->isLoggedIn()) {
        echo "<p>✅ User is logged in</p>";
        
        $user = $auth->getCurrentUser();
        if ($user) {
            echo "<p>✅ User loaded: " . $user->getEmail() . "</p>";
            
            // Test preferences
            $preferences = $user->getPreferences();
            echo "<p>✅ User preferences loaded</p>";
            echo "<pre>Preferences: " . print_r($preferences, true) . "</pre>";
            
            $defaultPrefs = UserPreferences::getDefaultPreferences();
            echo "<p>✅ Default preferences: " . print_r($defaultPrefs, true) . "</p>";
        } else {
            echo "<p>❌ Failed to load user</p>";
        }
    } else {
        echo "<p>ℹ️ User is not logged in - this is expected for API testing</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Auth system error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>4. Testing API Endpoint</h2>";

// Capture all output
ob_start();
try {
    // Set up the environment like a real API request
    $_SERVER['REQUEST_METHOD'] = 'GET';
    header('Content-Type: application/json');
    
    // Include the API endpoint
    include 'api/v1/auth/notification-preferences.php';
    
    $apiOutput = ob_get_contents();
    ob_end_clean();
    
    echo "<h3>API Output:</h3>";
    echo "<textarea style='width: 100%; height: 300px;'>" . htmlspecialchars($apiOutput) . "</textarea>";
    
    // Check if it's valid JSON
    $jsonData = json_decode($apiOutput, true);
    if ($jsonData === null) {
        echo "<p style='color: red;'>❌ API output is NOT valid JSON</p>";
        echo "<p>JSON Error: " . json_last_error_msg() . "</p>";
        
        // Check if there's HTML in the output
        if (strpos($apiOutput, '<') !== false) {
            echo "<p style='color: red;'>❌ HTML detected in output - this is causing the JSON parsing error!</p>";
        }
    } else {
        echo "<p style='color: green;'>✅ API output is valid JSON</p>";
        echo "<pre>" . print_r($jsonData, true) . "</pre>";
    }
} catch (Exception $e) {
    $errorOutput = ob_get_contents();
    ob_end_clean();
    
    echo "<p>❌ API Exception:</p>";
    echo "<p>Message: " . $e->getMessage() . "</p>";
    echo "<p>Output: " . htmlspecialchars($errorOutput) . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>