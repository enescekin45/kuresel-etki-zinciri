<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Debug info:\n";
echo "PHP version: " . phpversion() . "\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "Query string: " . ($_SERVER['QUERY_STRING'] ?? 'none') . "\n";
echo "Page parameter: " . ($_GET['page'] ?? 'not set') . "\n";

// Test URL parsing
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
echo "Path: " . $path . "\n";

$decoded_path = urldecode($path);
echo "Decoded path: " . $decoded_path . "\n";

$clean_path = str_replace(['/Küresel', '/K%C3%BCresel'], '', $decoded_path);
echo "Clean path: " . $clean_path . "\n";

// Test page routing
$page = $_GET['page'] ?? null;
if (!$page) {
    if ($clean_path === '/' || $clean_path === '' || $clean_path === '/index.php') {
        $page = 'home';
    } elseif ($clean_path === '/product') {
        $page = 'product';
    }
}

echo "Determined page: " . ($page ?? 'none') . "\n";
?>