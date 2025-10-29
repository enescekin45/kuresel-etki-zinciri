<?php
echo "Test page working\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "Page parameter: " . ($_GET['page'] ?? 'not set') . "\n";
?>