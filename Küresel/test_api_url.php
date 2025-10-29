<?php
// Test the exact URL that the frontend is using

echo "Testing API URL resolution\n";

// Simulate the URL that the frontend is using
$url = '/Küresel/api/products?search=Organik';
echo "Frontend URL: " . $url . "\n";

// Parse the URL like the router does
$path = parse_url($url, PHP_URL_PATH);
$path = urldecode($path); // Decode URL encoding
echo "Parsed path: " . $path . "\n";

// Remove base paths like the router does
$path = str_replace('/Küresel/api', '', $path); // Remove base path
$path = str_replace('/Kuresel/api', '', $path); // Handle non-encoded version too
echo "After removing base paths: " . $path . "\n";

// Handle both paths with and without /v1 prefix for backward compatibility
if (strpos($path, '/v1/') === 0) {
    $path = substr($path, 3); // Remove /v1 prefix
}
echo "Final path for routing: " . $path . "\n";

// Check if this matches the router
if ($path === '/products') {
    echo "SUCCESS: This path will match the router\n";
} else {
    echo "ERROR: This path will NOT match the router\n";
}

echo "\nTest completed\n";
?>