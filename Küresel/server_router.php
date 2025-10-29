<?php
// Router script for PHP built-in server
// This script handles routing for the built-in server

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Remove query string from URI
if (($pos = strpos($uri, '?')) !== false) {
    $uri = substr($uri, 0, $pos);
}

// Handle API requests
if (strpos($uri, '/Küresel/api/') === 0) {
    // Route to API handler
    require_once __DIR__ . '/api/router.php';
    exit;
}

// Handle QR code requests
if (strpos($uri, '/qr/') === 0) {
    // Route to QR code handler
    require_once __DIR__ . '/qr_handler.php';
    exit;
}

// For all other requests, serve the main index.php
// This handles frontend routing
require_once __DIR__ . '/index.php';
?>