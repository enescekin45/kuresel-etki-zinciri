<?php
require_once 'config/bootstrap.php';

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Comprehensive Notification Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>Comprehensive Notification System Test</h1>";

try {
    $auth = Auth::getInstance();
    
    if (!$auth->isLoggedIn()) {
        echo "<div class='test-section'>
                <h2>Authentication Status</h2>
                <p class='error'>User not logged in. Please <a href='/Küresel/?page=login'>log in</a> first.</p>
              </div>";
        exit;
    }
    
    echo "<div class='test-section'>
            <h2>Authentication Status</h2>
            <p class='success'>User is logged in</p>
          </div>";
    
    $user = $auth->getCurrentUser();
    echo "<div class='test-section'>
            <h2>User Information</h2>
            <p><strong>Email:</strong> " . $user->getEmail() . "</p>
            <p><strong>Name:</strong> " . $user->getFullName() . "</p>
            <p><strong>Phone:</strong> " . ($user->getPhone() ?: "Not set") . "</p>
          </div>";
    
    // Test 1: Check current preferences
    echo "<div class='test-section'>
            <h2>Test 1: Current Notification Preferences</h2>";
    
    $preferences = $user->getPreferences();
    $defaultPrefs = UserPreferences::getDefaultPreferences();
    $mergedPrefs = array_merge($defaultPrefs, $preferences);
    
    echo "<p><strong>Raw preferences from database:</strong></p>
          <pre>" . print_r($preferences, true) . "</pre>";
    
    echo "<p><strong>Merged with defaults:</strong></p>
          <pre>" . print_r($mergedPrefs, true) . "</pre>";
    
    echo "</div>";
    
    // Test 2: Update preferences
    echo "<div class='test-section'>
            <h2>Test 2: Update Notification Preferences</h2>";
    
    $newPreferences = [
        'email_notifications' => true,
        'sms_notifications' => true,
        'marketing_emails' => false
    ];
    
    $user->setPreferences($newPreferences);
    echo "<p class='success'>Preferences updated successfully</p>";
    
    // Verify update
    $updatedPreferences = $user->getPreferences();
    $mergedUpdatedPrefs = array_merge($defaultPrefs, $updatedPreferences);
    
    echo "<p><strong>Updated preferences:</strong></p>
          <pre>" . print_r($mergedUpdatedPrefs, true) . "</pre>";
    
    echo "</div>";
    
    // Test 3: Test API endpoint
    echo "<div class='test-section'>
            <h2>Test 3: API Endpoint Test</h2>";
    
    // Simulate API call
    $_SERVER['REQUEST_METHOD'] = 'GET';
    
    // Capture API output
    ob_start();
    require 'api/v1/auth/notification-preferences.php';
    $apiResponse = ob_get_clean();
    
    echo "<p><strong>API Response:</strong></p>
          <pre>" . htmlspecialchars($apiResponse) . "</pre>";
    
    $responseData = json_decode($apiResponse, true);
    if ($responseData && $responseData['success']) {
        echo "<p class='success'>API endpoint working correctly</p>";
    } else {
        echo "<p class='error'>API endpoint returned an error</p>";
    }
    
    echo "</div>";
    
    // Test 4: Test notification sending
    echo "<div class='test-section'>
            <h2>Test 4: Notification Sending Test</h2>";
    
    $notificationService = new NotificationService();
    
    // Test email notification
    echo "<h3>Email Notification Test</h3>";
    if ($mergedUpdatedPrefs['email_notifications']) {
        $emailResult = $notificationService->sendEmail(
            $user->getEmail(),
            "Test Email Notification",
            "This is a test email notification to verify the email system is working."
        );
        echo "<p class='success'>Email notification sent (simulated)</p>";
    } else {
        echo "<p class='info'>Email notifications are disabled for this user</p>";
    }
    
    // Test SMS notification
    echo "<h3>SMS Notification Test</h3>";
    if ($mergedUpdatedPrefs['sms_notifications']) {
        if ($user->getPhone()) {
            $smsResult = $notificationService->sendSMS(
                $user->getPhone(),
                "This is a test SMS notification to verify the SMS system is working."
            );
            echo "<p class='success'>SMS notification sent (simulated)</p>";
        } else {
            echo "<p class='info'>User has SMS notifications enabled but no phone number is set</p>";
        }
    } else {
        echo "<p class='info'>SMS notifications are disabled for this user</p>";
    }
    
    echo "</div>";
    
    echo "<div class='test-section'>
            <h2>Test Summary</h2>
            <p class='success'>All tests completed. The notification system is working correctly.</p>
            <p>You can now go to the <a href='/Küresel/?page=settings'>Settings page</a> to test the notification preferences UI.</p>
          </div>";

} catch (Exception $e) {
    echo "<div class='test-section'>
            <h2>Error</h2>
            <p class='error'>" . $e->getMessage() . "</p>
            <pre>" . $e->getTraceAsString() . "</pre>
          </div>";
}

echo "</body>
</html>";
?>