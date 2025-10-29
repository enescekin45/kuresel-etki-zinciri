<?php
/**
 * Product Detail API Endpoint
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
    // Log the request for debugging
    error_log("Product API called with GET params: " . print_r($_GET, true));
    
    // Check for UUID first, then ID, then product code, then barcode
    $uuid = $_GET['uuid'] ?? '';
    $id = $_GET['id'] ?? '';
    $productCode = $_GET['product_code'] ?? '';
    $barcode = $_GET['barcode'] ?? '';
    
    error_log("Parsed values - UUID: '$uuid', ID: '$id', Product Code: '$productCode', Barcode: '$barcode'");
    
    // Additional debugging
    error_log("Request URI: " . $_SERVER['REQUEST_URI']);
    error_log("Query String: " . $_SERVER['QUERY_STRING']);
    
    if (empty($uuid) && empty($id) && empty($productCode) && empty($barcode)) {
        throw new Exception('Product ID, UUID, product code, or barcode is required', 400);
    }
    
    $product = new Product();
    
    // Load product by UUID, ID, product code, or barcode
    if (!empty($uuid)) {
        $product->loadByUuid($uuid);
    } elseif (!empty($id)) {
        $product->loadById($id);
    } elseif (!empty($productCode)) {
        $product->loadByProductCode($productCode);
    } elseif (!empty($barcode)) {
        $product->loadByBarcode($barcode);
    }
    
    $productData = $product->toArray(true);
    
    // Enhance product data with additional information
    // Get batches
    $batches = $product->getBatches();
    $productData['batches'] = $batches;
    
    // If we have batches, get supply chain steps and impact scores for the first batch
    if (!empty($batches)) {
        $firstBatchId = $batches[0]['id'];
        
        // Get supply chain steps
        $supplyChainSteps = $product->getSupplyChainSteps($firstBatchId);
        $productData['supply_chain_steps'] = $supplyChainSteps;
        
        // Get impact scores
        $impactScores = $product->getImpactScores($firstBatchId);
        if (!empty($impactScores)) {
            $productData['impact_scores'] = $impactScores[0]; // Get the most recent impact score
        }
    }
    
    // Parse certificates from documentation field
    if (isset($productData['documentation']) && !empty($productData['documentation'])) {
        $certificates = json_decode($productData['documentation'], true);
        if (is_array($certificates)) {
            $productData['certificates'] = $certificates;
        } else {
            $productData['certificates'] = [];
        }
    } else {
        $productData['certificates'] = [];
    }
    
    // Ensure product images is properly formatted
    // FIXED: Handle both string and array formats correctly
    if (isset($productData['product_images'])) {
        if (is_string($productData['product_images'])) {
            $productData['product_images'] = json_decode($productData['product_images'], true) ?: [];
        } elseif (!is_array($productData['product_images'])) {
            $productData['product_images'] = [];
        }
        // If it's already an array, keep it as is
    } else {
        $productData['product_images'] = [];
    }
    
    $response = [
        'success' => true,
        'message' => 'Product retrieved successfully',
        'data' => $productData
    ];
    
} catch (Exception $e) {
    $statusCode = $e->getCode() ?: 404;
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