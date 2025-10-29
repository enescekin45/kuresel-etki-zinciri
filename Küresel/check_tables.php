<?php
define('CONFIG_DIR', __DIR__ . '/config');
define('FRONTEND_DIR', __DIR__ . '/frontend');
require_once 'classes/Database.php';

try {
    $db = Database::getInstance();
    $tables = $db->fetchAll('SHOW TABLES');
    
    echo "Database tables:\n";
    foreach($tables as $table) {
        echo "- " . $table['Tables_in_kuresel_etki_zinciri'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>