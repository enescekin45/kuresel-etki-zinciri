<?php
// Simple test to verify API endpoints are working
echo "<h1>API Endpoint Test</h1>";

// Test URLs
$endpoints = [
    '/Küresel/api/auth/notification-preferences',
    '/Küresel/api/auth/2fa-setup',
    '/Küresel/api/auth/devices',
    '/Küresel/api/auth/profile'
];

foreach ($endpoints as $endpoint) {
    echo "<h2>Testing: $endpoint</h2>";
    
    // Use cURL to test the endpoint
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://localhost$endpoint");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "<p>HTTP Status: $httpCode</p>";
    
    if ($httpCode == 401) {
        echo "<p style='color: orange;'>Expected result - Authentication required</p>";
    } elseif ($httpCode == 404) {
        echo "<p style='color: red;'>Error - Endpoint not found</p>";
    } else {
        echo "<p style='color: green;'>Endpoint accessible</p>";
    }
    
    echo "<hr>";
}
?>