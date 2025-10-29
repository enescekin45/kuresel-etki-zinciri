<?php
echo "Current directory: " . __DIR__ . "\n";
echo "Root dir: " . __DIR__ . '/../../../../..' . "\n";
echo "Config dir: " . __DIR__ . '/../../../../../../config' . "\n";
echo "Classes dir: " . __DIR__ . '/../../../../../../classes' . "\n";

// Test if files exist
$configFile = __DIR__ . '/../../../../../../config/database.php';
$classesDir = __DIR__ . '/../../../../../../classes';

echo "Config file exists: " . (file_exists($configFile) ? 'Yes' : 'No') . "\n";
echo "Classes directory exists: " . (is_dir($classesDir) ? 'Yes' : 'No') . "\n";

if (is_dir($classesDir)) {
    echo "Classes directory contents:\n";
    $files = scandir($classesDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "  - $file\n";
        }
    }
}
?>