<?php
echo "Server is working correctly\n";
echo "Current directory: " . getcwd() . "\n";
echo "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "\n";
echo "Page parameter: " . ($_GET['page'] ?? 'Not set') . "\n";

// List files in the pages directory
echo "Files in pages directory:\n";
$files = scandir('frontend/pages');
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        echo "  - " . $file . "\n";
    }
}
?>