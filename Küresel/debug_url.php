<?php
echo "<h1>URL Debug Information</h1>";

echo "<h2>Server Variables</h2>";
echo "<p><strong>REQUEST_URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>QUERY_STRING:</strong> " . ($_SERVER['QUERY_STRING'] ?? 'N/A') . "</p>";

// Parse the URL
$parsedUrl = parse_url($_SERVER['REQUEST_URI']);
echo "<p><strong>Parsed Path:</strong> " . ($parsedUrl['path'] ?? 'N/A') . "</p>";

// Decode the path
$decodedPath = urldecode($parsedUrl['path'] ?? '');
echo "<p><strong>Decoded Path:</strong> " . $decodedPath . "</p>";

// Remove base paths
$path = str_replace('/Küresel', '', $decodedPath);
$path = str_replace('/Kuresel', '', $path);
echo "<p><strong>Path after removing base:</strong> " . $path . "</p>";

// Check what page we're routing to
$page = 'home';
if ($path === '/' || $path === '') {
    $page = 'home';
} elseif ($path === '/about') {
    $page = 'about';
} elseif ($path === '/contact') {
    $page = 'contact';
} elseif ($path === '/login') {
    $page = 'login';
} elseif ($path === '/register') {
    $page = 'register';
} elseif ($path === '/forgot-password') {
    $page = 'forgot-password';
} elseif ($path === '/company') {
    $page = 'company';
} elseif ($path === '/consumer') {
    $page = 'consumer';
} elseif ($path === '/validator') {
    $page = 'validator';
} elseif ($path === '/team') {
    $page = 'team';
} elseif ($path === '/careers') {
    $page = 'careers';
} elseif ($path === '/press') {
    $page = 'press';
} elseif ($path === '/help') {
    $page = 'help';
} elseif ($path === '/docs') {
    $page = 'docs';
} elseif ($path === '/privacy') {
    $page = 'privacy';
} elseif ($path === '/terms') {
    $page = 'terms';
} elseif ($path === '/cookies') {
    $page = 'cookies';
} elseif ($path === '/profile') {
    $page = 'profile';
} elseif ($path === '/settings') {
    $page = 'settings';
} elseif ($path === '/change-password') {
    $page = 'change-password';
} elseif ($path === '/admin') {
    $page = 'admin';
} elseif ($path === '/product') {
    $page = 'product';
} elseif ($path === '/validation') {
    $page = 'validation';
}

echo "<p><strong>Determined page:</strong> " . $page . "</p>";

// Check if file exists
$pageFile = 'frontend/pages/' . $page . '.php';
echo "<p><strong>Page file path:</strong> " . $pageFile . "</p>";
echo "<p><strong>File exists:</strong> " . (file_exists($pageFile) ? 'Yes' : 'No') . "</p>";

if (file_exists($pageFile)) {
    echo "<p><strong>File size:</strong> " . filesize($pageFile) . " bytes</p>";
}

echo "<h2>Available page files</h2>";
$pagesDir = 'frontend/pages/';
if (is_dir($pagesDir)) {
    $files = scandir($pagesDir);
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            echo "<p>" . $file . "</p>";
        }
    }
}
?>