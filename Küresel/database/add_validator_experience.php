<?php
/**
 * Add experience_years column to validators table
 */

define('ROOT_DIR', dirname(__DIR__));
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

require_once CLASSES_DIR . '/Database.php';

try {
    $db = Database::getInstance();
    
    // Check if the column already exists
    $checkSql = "SHOW COLUMNS FROM validators LIKE 'experience_years'";
    $columnExists = $db->fetchRow($checkSql);
    
    if ($columnExists) {
        echo "✅ experience_years column already exists in validators table.\n";
    } else {
        // Add the experience_years column
        $sql = "ALTER TABLE validators ADD COLUMN experience_years INT DEFAULT 0 AFTER successful_validations";
        $db->execute($sql);
        echo "✅ experience_years column added to validators table.\n";
    }
    
    echo "✅ Migration completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>