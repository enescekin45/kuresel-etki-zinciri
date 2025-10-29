<?php
// Check companies in database

// Define ROOT_DIR constant
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

// Include required files
require_once ROOT_DIR . '/config/database.php';
require_once ROOT_DIR . '/classes/Database.php';

try {
    $db = Database::getInstance();
    
    // Check if there are existing companies
    $sql = "SELECT COUNT(*) as count FROM companies";
    $result = $db->fetchRow($sql);
    echo "Current companies in database: " . $result['count'] . "\n";
    
    if ($result['count'] > 0) {
        // Get existing companies
        $sql = "SELECT id, company_name, company_type FROM companies";
        $companies = $db->fetchAll($sql);
        echo "Existing companies:\n";
        foreach ($companies as $company) {
            echo "- " . $company['company_name'] . " (" . $company['company_type'] . ")\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>