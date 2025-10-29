<?php
// Check web root and test image paths

echo "Document root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Not set') . "\n";
echo "Current script: " . __FILE__ . "\n";
echo "Current directory: " . __DIR__ . "\n";

// Test the actual file paths
$testPaths = [
    '/Küresel/assets/images/products/bal.svg',
    '/assets/images/products/bal.svg',
    '/Küresel/assets/images/products/organik-zeytinyagi.svg',
    '/assets/images/products/organik-zeytinyagi.svg'
];

foreach ($testPaths as $path) {
    $fullPath = ($_SERVER['DOCUMENT_ROOT'] ?? 'c:\xampp\htdocs') . $path;
    echo "Path: $path\n";
    echo "Full path: $fullPath\n";
    echo "Exists: " . (file_exists($fullPath) ? "Yes" : "No") . "\n\n";
}

// Also test with the actual files
$actualFiles = [
    'c:\xampp\htdocs\Küresel\assets\images\products\bal.svg',
    'c:\xampp\htdocs\Küresel\assets\images\products\organik-zeytinyagi.svg'
];

foreach ($actualFiles as $file) {
    echo "File: $file\n";
    echo "Exists: " . (file_exists($file) ? "Yes" : "No") . "\n\n";
}
?>