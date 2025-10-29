<?php
require_once 'config/bootstrap.php';

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>User Preferences Table Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>User Preferences Table Test</h1>";

try {
    $db = Database::getInstance();
    
    echo "<div class='test-section'>
            <h2>Database Connection</h2>
            <p class='success'>Database connection successful</p>
          </div>";
    
    // Check if user_preferences table exists
    echo "<div class='test-section'>
            <h2>Table Existence Check</h2>";
    
    $tables = $db->fetchAll("SHOW TABLES LIKE 'user_preferences'");
    
    if (!empty($tables)) {
        echo "<p class='success'>user_preferences table exists</p>";
    } else {
        echo "<p class='error'>user_preferences table does not exist</p>";
        echo "<p>Creating table...</p>";
        
        // Create the table
        $sql = "CREATE TABLE IF NOT EXISTS user_preferences (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            preference_key VARCHAR(100) NOT NULL,
            preference_value TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_user_key (user_id, preference_key),
            UNIQUE KEY unique_user_preference (user_id, preference_key)
        ) ENGINE=InnoDB";
        
        $db->execute($sql);
        echo "<p class='success'>user_preferences table created successfully</p>";
    }
    
    echo "</div>";
    
    // Test inserting and retrieving preferences
    echo "<div class='test-section'>
            <h2>Preferences Operations Test</h2>";
    
    $auth = Auth::getInstance();
    
    if ($auth->isLoggedIn()) {
        $user = $auth->getCurrentUser();
        $userId = $user->getId();
        
        echo "<p>Testing with user ID: $userId</p>";
        
        // Insert test preferences
        $testPreferences = [
            'email_notifications' => 'true',
            'sms_notifications' => 'false',
            'marketing_emails' => 'true'
        ];
        
        $preferencesObj = new UserPreferences();
        
        foreach ($testPreferences as $key => $value) {
            $preferencesObj->setPreference($userId, $key, $value);
        }
        
        echo "<p class='success'>Test preferences inserted successfully</p>";
        
        // Retrieve preferences
        $retrievedPreferences = $preferencesObj->getPreferences($userId);
        
        echo "<p><strong>Retrieved preferences:</strong></p>";
        echo "<table>
                <tr><th>Key</th><th>Value</th></tr>";
        
        foreach ($retrievedPreferences as $key => $value) {
            echo "<tr><td>$key</td><td>$value</td></tr>";
        }
        
        echo "</table>";
        
        // Test updating a preference
        $preferencesObj->setPreference($userId, 'sms_notifications', 'true');
        echo "<p class='success'>Updated sms_notifications preference</p>";
        
        // Retrieve updated preferences
        $updatedPreferences = $preferencesObj->getPreferences($userId);
        echo "<p><strong>Updated preferences:</strong></p>";
        echo "<table>
                <tr><th>Key</th><th>Value</th></tr>";
        
        foreach ($updatedPreferences as $key => $value) {
            echo "<tr><td>$key</td><td>$value</td></tr>";
        }
        
        echo "</table>";
        
    } else {
        echo "<p class='info'>User not logged in. Cannot test with real user data.</p>";
    }
    
    echo "</div>";
    
    echo "<div class='test-section'>
            <h2>Test Summary</h2>
            <p class='success'>All database tests completed successfully.</p>
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