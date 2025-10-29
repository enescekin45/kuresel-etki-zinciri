<?php
/**
 * Database Installer
 * 
 * Installs and initializes the database schema
 */

require_once __DIR__ . '/../config/database.php';

class DatabaseInstaller {
    private $config;
    private $pdo;
    
    public function __construct() {
        $this->config = require __DIR__ . '/../config/database.php';
    }
    
    public function install() {
        try {
            echo "Starting database installation...\n";
            
            // Connect to MySQL server without specifying database
            $this->connectToServer();
            
            // Create database if it doesn't exist
            $this->createDatabase();
            
            // Connect to the specific database
            $this->connectToDatabase();
            
            // Run the schema
            $this->runSchema();
            
            echo "Database installation completed successfully!\n";
            return true;
            
        } catch (Exception $e) {
            echo "Database installation failed: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    private function connectToServer() {
        $dbConfig = $this->config['database'];
        
        $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};charset={$dbConfig['charset']}";
        
        $this->pdo = new PDO(
            $dsn,
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['options']
        );
        
        echo "Connected to MySQL server.\n";
    }
    
    private function createDatabase() {
        $dbName = $this->config['database']['dbname'];
        
        $sql = "CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        $this->pdo->exec($sql);
        
        echo "Database '{$dbName}' created or verified.\n";
    }
    
    private function connectToDatabase() {
        $dbConfig = $this->config['database'];
        
        $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
        
        $this->pdo = new PDO(
            $dsn,
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['options']
        );
        
        echo "Connected to database '{$dbConfig['dbname']}'.\n";
    }
    
    private function runSchema() {
        $schemaFile = __DIR__ . '/../database/schema.sql';
        
        if (!file_exists($schemaFile)) {
            throw new Exception("Schema file not found: {$schemaFile}");
        }
        
        $schema = file_get_contents($schemaFile);
        
        // Split the schema into individual statements
        $statements = $this->splitSqlStatements($schema);
        
        echo "Executing " . count($statements) . " SQL statements...\n";
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (empty($statement) || strpos($statement, '--') === 0) {
                continue;
            }
            
            try {
                $this->pdo->exec($statement);
            } catch (PDOException $e) {
                echo "Warning: " . $e->getMessage() . "\n";
                echo "Statement: " . substr($statement, 0, 100) . "...\n";
            }
        }
        
        echo "Schema executed successfully.\n";
    }
    
    private function splitSqlStatements($sql) {
        // Remove comments and split by semicolon
        $sql = preg_replace('/--.*$/m', '', $sql);
        $statements = preg_split('/;\s*$/m', $sql);
        
        return array_filter($statements, function($statement) {
            return !empty(trim($statement));
        });
    }
    
    public function checkInstallation() {
        try {
            $this->connectToDatabase();
            
            // Check if key tables exist
            $tables = ['users', 'companies', 'products', 'supply_chain_steps', 'impact_scores'];
            
            foreach ($tables as $table) {
                $stmt = $this->pdo->query("SHOW TABLES LIKE '{$table}'");
                if ($stmt->rowCount() == 0) {
                    return false;
                }
            }
            
            echo "Database installation verified successfully.\n";
            return true;
            
        } catch (Exception $e) {
            echo "Database check failed: " . $e->getMessage() . "\n";
            return false;
        }
    }
}

// Run installer if called directly
if (php_sapi_name() === 'cli' && __FILE__ === $_SERVER['SCRIPT_FILENAME']) {
    $installer = new DatabaseInstaller();
    
    if ($installer->install()) {
        echo "\n✅ Database installation completed successfully!\n";
        echo "You can now use the Global Impact Chain platform.\n";
    } else {
        echo "\n❌ Database installation failed!\n";
        exit(1);
    }
}
?>