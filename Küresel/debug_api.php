<?php
// Debug the API endpoint
echo "<h1>Debugging API Endpoint</h1>";

// Start session like the main application does
session_start();

// Set error reporting to show all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define the same constants as in index.php
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');
define('API_DIR', ROOT_DIR . '/api');
define('FRONTEND_DIR', ROOT_DIR . '/frontend');

// Autoloader for classes
spl_autoload_register(function ($class_name) {
    $file = CLASSES_DIR . '/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Include configuration files
require_once CONFIG_DIR . '/database.php';
require_once CONFIG_DIR . '/app.php';

echo "<h2>Testing Auth Instance</h2>";

try {
    // Test if we can create an Auth instance
    $auth = Auth::getInstance();
    echo "<p>✅ Auth instance created successfully</p>";
    
    // Check if user is logged in
    if ($auth->isLoggedIn()) {
        echo "<p>✅ User is logged in</p>";
        
        // Get current user
        $currentUser = $auth->getCurrentUser();
        if ($currentUser) {
            echo "<p>✅ Current user loaded: " . $currentUser->getEmail() . "</p>";
            echo "<p>User type: " . $currentUser->getUserType() . "</p>";
        } else {
            echo "<p>❌ Failed to load current user</p>";
        }
    } else {
        echo "<p>❌ User is not logged in</p>";
        echo "<p><a href='/Küresel/?page=login'>Login here</a></p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>Testing API Endpoint Directly</h2>";

// Capture output to see what's actually being returned
ob_start();
try {
    // Simulate GET request
    $_SERVER['REQUEST_METHOD'] = 'GET';
    
    // Include the API endpoint
    include 'api/v1/auth/notification-preferences.php';
    
    $output = ob_get_contents();
    ob_end_clean();
    
    echo "<h3>Raw API Output:</h3>";
    echo "<textarea style='width: 100%; height: 200px;'>" . htmlspecialchars($output) . "</textarea>";
    
    // Check if it's valid JSON
    $jsonData = json_decode($output, true);
    if ($jsonData === null) {
        echo "<p style='color: red;'>❌ Output is NOT valid JSON</p>";
        echo "<p>JSON Error: " . json_last_error_msg() . "</p>";
    } else {
        echo "<p style='color: green;'>✅ Output is valid JSON</p>";
        echo "<pre>" . print_r($jsonData, true) . "</pre>";
    }
} catch (Exception $e) {
    $errorOutput = ob_get_contents();
    ob_end_clean();
    
    echo "<p>❌ Exception occurred:</p>";
    echo "<p>Message: " . $e->getMessage() . "</p>";
    echo "<p>Error Output: " . htmlspecialchars($errorOutput) . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>