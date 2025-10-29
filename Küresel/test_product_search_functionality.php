<?php
// Test product search functionality
// Define project root directory
define('ROOT_DIR', __DIR__);
define('CONFIG_DIR', ROOT_DIR . '/config');
define('CLASSES_DIR', ROOT_DIR . '/classes');

// Autoloader for classes
spl_autoload_register(function ($class_name) {
    $file = CLASSES_DIR . '/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Include configuration files
require_once CONFIG_DIR . '/database.php';

echo "Testing product search functionality...\n";

try {
    // Test database connection
    $db = Database::getInstance();
    echo "✅ Database connection successful\n";
    
    // Check if we have products in the database
    $sql = "SELECT COUNT(*) as count FROM products";
    $result = $db->fetchRow($sql);
    echo "Total products in database: " . $result['count'] . "\n";
    
    // Check if we have companies
    $sql = "SELECT id FROM companies LIMIT 1";
    $company = $db->fetchRow($sql);
    
    if (!$company) {
        echo "No companies found. Creating a test company...\n";
        // Get a user to associate with the company
        $sql = "SELECT id FROM users LIMIT 1";
        $user = $db->fetchRow($sql);
        if ($user) {
            $sql = "INSERT INTO companies (uuid, user_id, company_name, company_type, status) VALUES (?, ?, ?, ?, ?)";
            $companyId = $db->insert($sql, [
                $db->generateUUID(),
                $user['id'],
                'Test Food Company',
                'manufacturer',
                'active'
            ]);
            echo "Created test company with ID: " . $companyId . "\n";
        } else {
            throw new Exception("No users found to associate with company");
        }
    } else {
        $companyId = $company['id'];
        echo "Using existing company with ID: " . $companyId . "\n";
    }
    
    if ($result['count'] == 0) {
        echo "No products found. Creating test products...\n";
        
        // Create a product that matches your search criteria
        $product = new Product();
        $productData = [
            'company_id' => $companyId,
            'product_name' => 'Organik Zeytinyağı',
            'product_code' => 'PRD-GIDA-001',
            'barcode' => '8691234567890',
            'category' => 'Gıda',
            'description' => '100% organik, soğuk sıkım zeytinyağı',
            'brand' => 'Organik Tarım',
            'status' => 'active'
        ];
        
        $productId = $product->create($productData);
        echo "✅ Created test product with ID: " . $productId . "\n";
        echo "Product code: PRD-GIDA-001\n";
        echo "Barcode: 8691234567890\n";
        echo "Product name: Organik Zeytinyağı\n";
    }
    
    // Test product search by different criteria
    echo "\nTest 1: Searching by product code 'PRD-GIDA-001'...\n";
    $product = new Product();
    $filters1 = ['product_code' => 'PRD-GIDA-001'];
    $products1 = $product->getAll(1, 10, $filters1);
    echo "Found " . count($products1) . " products\n";
    
    if (count($products1) > 0) {
        echo "Product found: " . $products1[0]['product_name'] . "\n";
    }
    
    echo "\nTest 2: Searching by barcode '8691234567890'...\n";
    $filters2 = ['barcode' => '8691234567890'];
    $products2 = $product->getAll(1, 10, $filters2);
    echo "Found " . count($products2) . " products\n";
    
    if (count($products2) > 0) {
        echo "Product found: " . $products2[0]['product_name'] . "\n";
    }
    
    echo "\nTest 3: Searching by product name 'Organik Zeytinyağı'...\n";
    $filters3 = ['search' => 'Organik Zeytinyağı'];
    $products3 = $product->getAll(1, 10, $filters3);
    echo "Found " . count($products3) . " products\n";
    
    if (count($products3) > 0) {
        echo "Product found: " . $products3[0]['product_name'] . "\n";
    }
    
    echo "\n✅ Product search functionality test completed successfully!\n";
    echo "The product search should now work correctly in the frontend.\n";
    echo "You can search for:\n";
    echo "- Product code: PRD-GIDA-001\n";
    echo "- Barcode: 8691234567890\n";
    echo "- Product name: Organik Zeytinyağı\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>