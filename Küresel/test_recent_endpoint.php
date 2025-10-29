<?php
// Start session to simulate a logged-in user
session_start();

// Set session data to simulate a logged-in company user
$_SESSION['logged_in'] = true;
$_SESSION['user_id'] = 20; // This should be a user that is a company
$_SESSION['user_type'] = 'company';
$_SESSION['user_email'] = 'test@company.com';
$_SESSION['user_name'] = 'Test Company User';
$_SESSION['login_time'] = time();

// Include the recent products API endpoint
include 'api/v1/company/products/recent.php';
?>