<?php
/**
 * Products List API Endpoint
 */

// Define required constants only if not already defined
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', __DIR__ . '/../../../');
}

if (!defined('CONFIG_DIR')) {
    define('CONFIG_DIR', ROOT_DIR . '/config');
}

if (!defined('CLASSES_DIR')) {
    define('CLASSES_DIR', ROOT_DIR . '/classes');
}

// Include required files
require_once CONFIG_DIR . '/database.php';
require_once CLASSES_DIR . '/Database.php';
require_once CLASSES_DIR . '/Product.php';

try {
    // Get pagination parameters
    $page = max(1, intval($_GET['page'] ?? 1));
    $limit = min(100, max(10, intval($_GET['limit'] ?? 20)));
    
    // Get search parameters
    $search = $_GET['search'] ?? '';
    $productCode = $_GET['product_code'] ?? '';
    $barcode = $_GET['barcode'] ?? '';
    
    // Create product instance
    $product = new Product();
    
    // Search for products
    $filters = [];
    if (!empty($productCode)) {
        $filters['product_code'] = $productCode;
    } elseif (!empty($barcode)) {
        $filters['barcode'] = $barcode;
    } elseif (!empty($search)) {
        $filters['search'] = $search;
    }
    
    $products = $product->getAll($page, $limit, $filters);
    $totalCount = $product->getTotalCount($filters);
    
    // Enhance products with more detailed information
    $enhancedProducts = [];
    foreach ($products as $productData) {
        // Add product images if available
        if (!empty($productData['product_images'])) {
            $productData['product_images'] = json_decode($productData['product_images'], true);
        } else {
            $productData['product_images'] = [];
        }
        
        // Ensure brand information is included
        if (!isset($productData['brand'])) {
            $productData['brand'] = null;
        }
        
        $enhancedProducts[] = $productData;
    }
    
    // Provide a more informative message based on results
    $message = 'Products retrieved successfully';
    if ($totalCount == 0) {
        if (!empty($productCode)) {
            $message = 'Ürün kodu "' . $productCode . '" ile eşleşen ürün bulunamadı';
        } elseif (!empty($barcode)) {
            $message = 'Barkod "' . $barcode . '" ile eşleşen ürün bulunamadı';
        } elseif (!empty($search)) {
            $message = '"' . $search . '" aramasıyla eşleşen ürün bulunamadı';
        } else {
            $message = 'Veritabanında ürün bulunamadı';
        }
    }
    
    $response = [
        'success' => true,
        'message' => $message,
        'data' => [
            'products' => $enhancedProducts,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $totalCount,
                'pages' => ceil($totalCount / $limit)
            ]
        ]
    ];
    
} catch (Exception $e) {
    $statusCode = $e->getCode() ?: 400;
    http_response_code($statusCode);
    
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => $statusCode
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>