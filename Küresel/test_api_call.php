<?php
// Start session to maintain session state
session_start();

// Display session information
echo "<h2>Session Information</h2>\n";
echo "<p>Session ID: " . session_id() . "</p>\n";
echo "<p>Session data:</p>\n";
echo "<pre>" . print_r($_SESSION, true) . "</pre>\n";

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    echo "<p>User is not logged in. Setting up a test session...</p>\n";
    
    // Set up a test session for a company user
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = 20; // This should be a user that is a company
    $_SESSION['user_type'] = 'company';
    $_SESSION['user_email'] = 'test@company.com';
    $_SESSION['user_name'] = 'Test Company User';
    $_SESSION['login_time'] = time();
    
    echo "<p>Test session set up. Session data:</p>\n";
    echo "<pre>" . print_r($_SESSION, true) . "</pre>\n";
} else {
    echo "<p>User is logged in.</p>\n";
}

// Now make the API call using cURL
echo "<h2>Making API Call</h2>\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost/Küresel/api/company/products/recent");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=" . session_id());

$response = curl_exec($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($response, 0, $header_size);
$body = substr($response, $header_size);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

if ($response === false) {
    echo "<p>cURL Error: " . curl_error($ch) . "</p>\n";
} else {
    echo "<p>HTTP Status Code: " . $http_code . "</p>\n";
    echo "<p>Headers:</p>\n";
    echo "<pre>" . htmlspecialchars($headers) . "</pre>\n";
    echo "<p>Response Body:</p>\n";
    echo "<pre>" . htmlspecialchars($body) . "</pre>\n";
}
?>