<?php
/**
 * QR Code Handler
 * 
 * Handles QR code requests and serves QR code images
 */

// Get the QR code path from URL
$requestPath = $_SERVER['REQUEST_URI'];
$qrPath = str_replace('/Küresel/qr/', '', $requestPath);

// Remove query parameters
if (strpos($qrPath, '?') !== false) {
    $qrPath = substr($qrPath, 0, strpos($qrPath, '?'));
}

// Security check - only allow PNG files
if (!preg_match('/^[a-zA-Z0-9_-]+\.png$/', $qrPath)) {
    http_response_code(404);
    exit('QR code not found');
}

$qrCodePath = QR_CODES_DIR . '/' . $qrPath;

// Check if file exists
if (!file_exists($qrCodePath)) {
    http_response_code(404);
    exit('QR code not found');
}

// Serve the file
header('Content-Type: image/png');
header('Content-Length: ' . filesize($qrCodePath));
header('Cache-Control: public, max-age=86400'); // Cache for 24 hours
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');

readfile($qrCodePath);
?>