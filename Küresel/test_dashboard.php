<?php
// Start session
session_start();

// Set up a test session for a company user
$_SESSION['logged_in'] = true;
$_SESSION['user_id'] = 20; // This should be a user that is a company
$_SESSION['user_type'] = 'company';
$_SESSION['user_email'] = 'test@company.com';
$_SESSION['user_name'] = 'Test Company User';
$_SESSION['login_time'] = time();

// Now include the company dashboard page
include 'frontend/pages/company.php';
?>