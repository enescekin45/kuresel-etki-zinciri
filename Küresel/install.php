<?php
/**
 * Installation and Setup Script
 * 
 * Sets up the database and verifies system requirements
 */

// Change to project root directory
chdir(__DIR__);

// Define constants
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

echo "=== Küresel Etki Zinciri Installation ===\n\n";

// Check PHP version
echo "1. Checking PHP version...\n";
if (version_compare(PHP_VERSION, '8.0.0', '<')) {
    die("Error: PHP 8.0 or higher is required. Current version: " . PHP_VERSION . "\n");
}
echo "✓ PHP version: " . PHP_VERSION . "\n\n";

// Check required extensions
echo "2. Checking required PHP extensions...\n";
$requiredExtensions = ['pdo', 'pdo_mysql', 'json', 'curl', 'gd'];
foreach ($requiredExtensions as $extension) {
    if (!extension_loaded($extension)) {
        die("Error: PHP extension '{$extension}' is required but not installed.\n");
    }
    echo "✓ {$extension}\n";
}
echo "\n";

// Check if .env file exists
echo "3. Checking environment configuration...\n";
if (!file_exists('.env')) {
    echo "! .env file not found. Copying from .env.example...\n";
    if (file_exists('.env.example')) {
        copy('.env.example', '.env');
        echo "✓ .env file created. Please edit it with your configuration.\n";
    } else {
        echo "! .env.example not found. Please create .env file manually.\n";
    }
} else {
    echo "✓ .env file exists\n";
}
echo "\n";

// Load environment variables
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    $envLines = explode("\n", $envContent);
    foreach ($envLines as $line) {
        if (strpos($line, '=') !== false && !str_starts_with(trim($line), '#')) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Check database connection
echo "4. Testing database connection...\n";
try {
    require_once CLASSES_DIR . '/Database.php';
    $db = Database::getInstance();
    $db->getConnection();
    echo "✓ Database connection successful\n\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    echo "Please check your database configuration in .env file.\n\n";
}

// Check if database schema exists
echo "5. Checking database schema...\n";
try {
    $db = Database::getInstance();
    $result = $db->fetchRow("SHOW TABLES LIKE 'users'");
    
    if ($result) {
        echo "✓ Database schema appears to be installed\n";
        
        // Check if admin user exists
        $adminUser = $db->fetchRow("SELECT * FROM users WHERE user_type = 'admin' LIMIT 1");
        if ($adminUser) {
            echo "✓ Admin user exists\n";
        } else {
            echo "! No admin user found\n";
        }
        
    } else {
        echo "! Database schema not found\n";
        echo "Would you like to install the database schema? (y/n): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        
        if (trim(strtolower($line)) === 'y' || trim(strtolower($line)) === 'yes') {
            echo "Installing database schema...\n";
            
            // Use the safe installation script
            $schemaSql = file_get_contents('database/install_safe.sql');
            if ($schemaSql) {
                // Split into individual statements
                $statements = array_filter(array_map('trim', explode(';', $schemaSql)));
                
                foreach ($statements as $statement) {
                    if (!empty($statement) && !preg_match('/^(\s*--|\/\*)/', $statement)) {
                        try {
                            $db->getConnection()->exec($statement);
                        } catch (Exception $e) {
                            // Ignore "already exists" errors for graceful installation
                            if (!str_contains($e->getMessage(), 'already exists')) {
                                echo "Warning: " . $e->getMessage() . "\n";
                            }
                        }
                    }
                }
                
                echo "✓ Database schema installed successfully\n";
            } else {
                echo "✗ Could not read install_safe.sql file\n";
            }
        }
        fclose($handle);
    }
} catch (Exception $e) {
    echo "✗ Database schema check failed: " . $e->getMessage() . "\n";
    echo "\nTroubleshooting tips:\n";
    echo "1. Make sure MySQL is running\n";
    echo "2. Check database credentials in .env file\n";
    echo "3. Create database manually: CREATE DATABASE kuresel_etki_zinciri;\n";
    echo "4. Run: mysql -u root -p kuresel_etki_zinciri < database/install_safe.sql\n";
}
echo "\n";

// Check directories and permissions
echo "6. Checking directory permissions...\n";
$directories = [
    'uploads' => 'uploads/',
    'qr-codes' => 'qr-codes/',
    'logs' => 'logs/'
];

foreach ($directories as $name => $path) {
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
        echo "✓ Created {$name} directory\n";
    } else {
        echo "✓ {$name} directory exists\n";
    }
    
    if (!is_writable($path)) {
        echo "! {$name} directory is not writable\n";
    } else {
        echo "✓ {$name} directory is writable\n";
    }
}
echo "\n";

// Check Composer dependencies
echo "7. Checking Composer dependencies...\n";
if (!file_exists('vendor/autoload.php')) {
    echo "! Composer dependencies not installed\n";
    echo "Please run: composer install\n";
} else {
    echo "✓ Composer dependencies installed\n";
}
echo "\n";

// Test core functionality
echo "8. Testing core functionality...\n";
try {
    // Test autoloader
    require_once 'vendor/autoload.php';
    
    // Test classes
    $testClasses = ['User', 'Company', 'Product', 'Validator', 'Auth', 'QRCodeGenerator', 'BlockchainIntegration'];
    foreach ($testClasses as $className) {
        if (class_exists($className)) {
            echo "✓ {$className} class loaded\n";
        } else {
            echo "✗ {$className} class not found\n";
        }
    }
    
} catch (Exception $e) {
    echo "✗ Core functionality test failed: " . $e->getMessage() . "\n";
}
echo "\n";

// Installation summary
echo "=== Installation Summary ===\n";
echo "Project: Küresel Etki Zinciri (Global Impact Chain)\n";
echo "Location: " . ROOT_DIR . "\n";
echo "URL: http://localhost/Küresel\n\n";

echo "Next steps:\n";
echo "1. Configure your .env file with proper database credentials\n";
echo "2. Run 'composer install' if not already done\n";
echo "3. Make sure your web server is pointing to this directory\n";
echo "4. Access the application at: http://localhost/Küresel\n";
echo "5. Login with admin credentials: admin@kuresaletzinciri.com / password\n\n";

echo "For development:\n";
echo "- API Documentation: /api-docs\n";
echo "- Database Schema: /database/schema.sql\n";
echo "- Configuration: /config/\n\n";

echo "Installation completed!\n";
?>
```
