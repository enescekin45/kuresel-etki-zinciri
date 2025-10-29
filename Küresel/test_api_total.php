<?php
// Simulate API call to get total validations
$_SERVER['REQUEST_METHOD'] = 'GET';
$_GET = [];

// Include the API endpoint directly
include 'api/v1/validator/stats/total.php';
?>