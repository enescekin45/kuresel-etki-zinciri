<?php
/**
 * Check session status
 */

session_start();

echo "Session data:\n";
print_r($_SESSION);

echo "\nServer data:\n";
print_r($_SERVER);
?>