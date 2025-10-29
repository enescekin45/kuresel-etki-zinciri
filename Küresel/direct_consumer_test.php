<?php
// Direct test of consumer panel
echo "<h1>Direct Consumer Panel Test</h1>";

// Set error reporting to show all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<p>Current working directory: " . getcwd() . "</p>";

// Try to include config
if (file_exists('config/bootstrap.php')) {
    echo "<p>✅ bootstrap.php found</p>";
    require_once 'config/bootstrap.php';
    echo "<p>✅ bootstrap.php loaded</p>";
} else {
    echo "<p>❌ bootstrap.php not found</p>";
}

// Try to include consumer panel directly
echo "<h2>Attempting to include consumer.php directly:</h2>";

if (file_exists('frontend/pages/consumer.php')) {
    echo "<p>✅ consumer.php file exists</p>";
    echo "<p>File size: " . filesize('frontend/pages/consumer.php') . " bytes</p>";
    
    // Try to include the file
    try {
        echo "<p>Attempting to include consumer.php...</p>";
        include 'frontend/pages/consumer.php';
        echo "<p>✅ consumer.php included successfully</p>";
    } catch (Exception $e) {
        echo "<p>❌ Error including consumer.php: " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
} else {
    echo "<p>❌ consumer.php file does not exist</p>";
    
    // List files in directory
    $files = scandir('frontend/pages/');
    echo "<p>Files in frontend/pages/:</p>";
    foreach ($files as $file) {
        if (strpos($file, '.php') !== false) {
            echo "<p>- " . $file . "</p>";
        }
    }
}
?>