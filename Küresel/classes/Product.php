<?php
/**
 * Product Model Class
 * 
 * Handles product management operations including registration,
 * batch tracking, and supply chain integration
 */

class Product {
    private $db;
    private $id;
    private $uuid;
    private $companyId;
    private $productName;
    private $productCode;
    private $barcode;
    private $category;
    private $status;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new product
     */
    public function create($data) {
        // Validate required fields
        $required = ['company_id', 'product_name', 'category'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Field '{$field}' is required");
            }
        }
        
        // Generate UUID
        $uuid = $this->db->generateUUID();
        
        // Generate product code if not provided
        $productCode = $data['product_code'] ?? $this->generateProductCode($data['company_id']);
        
        $sql = "INSERT INTO products (uuid, company_id, product_name, product_code, barcode, 
                category, subcategory, brand, description, weight, volume, dimensions, 
                packaging_type, shelf_life, origin_country, origin_region, harvest_season,
                product_images, documentation, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $uuid,
            $data['company_id'],
            $data['product_name'],
            $productCode,
            $data['barcode'] ?? null,
            $data['category'],
            $data['subcategory'] ?? null,
            $data['brand'] ?? null,
            $data['description'] ?? null,
            $data['weight'] ?? null,
            $data['volume'] ?? null,
            isset($data['dimensions']) ? json_encode($data['dimensions']) : null,
            $data['packaging_type'] ?? null,
            $data['shelf_life'] ?? null,
            $data['origin_country'] ?? null,
            $data['origin_region'] ?? null,
            $data['harvest_season'] ?? null,
            isset($data['product_images']) ? json_encode($data['product_images']) : null,
            isset($data['documentation']) ? json_encode($data['documentation']) : null,
            $data['status'] ?? 'active'
        ];
        
        $productId = $this->db->insert($sql, $params);
        
        // Generate QR Code
        $this->generateQRCode($productId);
        
        // Load the created product
        $this->loadById($productId);
        
        return $productId;
    }
    
    /**
     * Load product by ID
     */
    public function loadById($id) {
        error_log("Loading product by ID: $id");
        $sql = "SELECT p.*, c.company_name, c.company_type 
                FROM products p 
                JOIN companies c ON p.company_id = c.id 
                WHERE p.id = ?";
        $product = $this->db->fetchRow($sql, [$id]);
        
        if (!$product) {
            error_log("Product not found for ID: $id");
            throw new Exception("Product not found");
        }
        
        error_log("Product loaded: " . $product['product_name'] . " (ID: " . $product['id'] . ")");
        $this->populateFromArray($product);
        return $this;
    }
    
    /**
     * Load product by UUID
     */
    public function loadByUuid($uuid) {
        error_log("Loading product by UUID: $uuid");
        $sql = "SELECT p.*, c.company_name, c.company_type 
                FROM products p 
                JOIN companies c ON p.company_id = c.id 
                WHERE p.uuid = ?";
        $product = $this->db->fetchRow($sql, [$uuid]);
        
        if (!$product) {
            error_log("Product not found for UUID: $uuid");
            throw new Exception("Product not found");
        }
        
        error_log("Product loaded: " . $product['product_name'] . " (UUID: " . $product['uuid'] . ")");
        $this->populateFromArray($product);
        return $this;
    }
    
    /**
     * Load product by product code
     */
    public function loadByProductCode($productCode) {
        $sql = "SELECT p.*, c.company_name, c.company_type 
                FROM products p 
                JOIN companies c ON p.company_id = c.id 
                WHERE p.product_code = ?";
        $product = $this->db->fetchRow($sql, [$productCode]);
        
        if (!$product) {
            throw new Exception("Product not found");
        }
        
        $this->populateFromArray($product);
        return $this;
    }
    
    /**
     * Load product by barcode
     */
    public function loadByBarcode($barcode) {
        $sql = "SELECT p.*, c.company_name, c.company_type 
                FROM products p 
                JOIN companies c ON p.company_id = c.id 
                WHERE p.barcode = ?";
        $product = $this->db->fetchRow($sql, [$barcode]);
        
        if (!$product) {
            throw new Exception("Product not found");
        }
        
        $this->populateFromArray($product);
        return $this;
    }
    
    /**
     * Update product information
     */
    public function update($data) {
        if (!$this->id) {
            throw new Exception("Product not loaded");
        }
        
        $allowedFields = [
            'product_name', 'barcode', 'category', 'subcategory', 'brand', 'description',
            'weight', 'volume', 'packaging_type', 'shelf_life', 'origin_country', 
            'origin_region', 'harvest_season', 'status'
        ];
        
        $updateFields = [];
        $params = [];
        
        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                if ($field === 'dimensions' || $field === 'product_images' || $field === 'documentation') {
                    $updateFields[] = "{$field} = ?";
                    $params[] = json_encode($value);
                } else {
                    $updateFields[] = "{$field} = ?";
                    $params[] = $value;
                }
            }
        }
        
        if (empty($updateFields)) {
            throw new Exception("No valid fields to update");
        }
        
        $params[] = $this->id;
        
        $sql = "UPDATE products SET " . implode(', ', $updateFields) . 
               ", updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        
        $this->db->execute($sql, $params);
        
        // Reload product data
        $this->loadById($this->id);
        
        return true;
    }
    
    /**
     * Create a new batch for this product
     */
    public function createBatch($batchData) {
        if (!$this->id) {
            throw new Exception("Product not loaded");
        }
        
        // Validate required fields
        $required = ['batch_number', 'quantity'];
        foreach ($required as $field) {
            if (empty($batchData[$field])) {
                throw new Exception("Field '{$field}' is required for batch creation");
            }
        }
        
        // Check if batch number already exists for this product
        $sql = "SELECT COUNT(*) as count FROM product_batches WHERE product_id = ? AND batch_number = ?";
        $result = $this->db->fetchRow($sql, [$this->id, $batchData['batch_number']]);
        
        if ($result['count'] > 0) {
            throw new Exception("Batch number already exists for this product");
        }
        
        // Generate UUID for batch
        $uuid = $this->db->generateUUID();
        
        $sql = "INSERT INTO product_batches (uuid, product_id, batch_number, production_date, 
                expiry_date, quantity, unit, production_facility, production_line, quality_grade, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $uuid,
            $this->id,
            $batchData['batch_number'],
            $batchData['production_date'] ?? null,
            $batchData['expiry_date'] ?? null,
            $batchData['quantity'],
            $batchData['unit'] ?? 'pieces',
            $batchData['production_facility'] ?? null,
            $batchData['production_line'] ?? null,
            $batchData['quality_grade'] ?? null,
            $batchData['status'] ?? 'in_production'
        ];
        
        $batchId = $this->db->insert($sql, $params);
        
        return $batchId;
    }
    
    /**
     * Get all batches for this product
     */
    public function getBatches($page = 1, $limit = 20) {
        if (!$this->id) {
            throw new Exception("Product not loaded");
        }
        
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT * FROM product_batches WHERE product_id = ? 
                ORDER BY created_at DESC LIMIT ? OFFSET ?";
        
        return $this->db->fetchAll($sql, [$this->id, $limit, $offset]);
    }
    
    /**
     * Get supply chain steps for all batches of this product
     */
    public function getSupplyChainSteps($batchId = null) {
        if (!$this->id) {
            throw new Exception("Product not loaded");
        }
        
        if ($batchId) {
            $sql = "SELECT scs.*, c.company_name, c.company_type 
                    FROM supply_chain_steps scs
                    JOIN companies c ON scs.company_id = c.id
                    WHERE scs.product_batch_id = ?
                    ORDER BY scs.step_order ASC, scs.created_at ASC";
            return $this->db->fetchAll($sql, [$batchId]);
        } else {
            $sql = "SELECT scs.*, c.company_name, c.company_type, pb.batch_number
                    FROM supply_chain_steps scs
                    JOIN companies c ON scs.company_id = c.id
                    JOIN product_batches pb ON scs.product_batch_id = pb.id
                    WHERE pb.product_id = ?
                    ORDER BY pb.batch_number, scs.step_order ASC, scs.created_at ASC";
            return $this->db->fetchAll($sql, [$this->id]);
        }
    }
    
    /**
     * Get impact scores for product batches
     */
    public function getImpactScores($batchId = null) {
        if (!$this->id) {
            throw new Exception("Product not loaded");
        }
        
        if ($batchId) {
            $sql = "SELECT * FROM impact_scores WHERE product_batch_id = ? 
                    ORDER BY calculation_date DESC";
            return $this->db->fetchAll($sql, [$batchId]);
        } else {
            $sql = "SELECT isc.*, pb.batch_number 
                    FROM impact_scores isc
                    JOIN product_batches pb ON isc.product_batch_id = pb.id
                    WHERE pb.product_id = ?
                    ORDER BY pb.batch_number, isc.calculation_date DESC";
            return $this->db->fetchAll($sql, [$this->id]);
        }
    }
    
    /**
     * Generate product code
     */
    private function generateProductCode($companyId) {
        // Get company info
        $sql = "SELECT id FROM companies WHERE id = ?";
        $company = $this->db->fetchRow($sql, [$companyId]);
        
        if (!$company) {
            throw new Exception("Company not found");
        }
        
        // Generate unique product code: COMP{company_id}-PROD{timestamp}
        $timestamp = time();
        $productCode = "COMP{$companyId}-PROD{$timestamp}";
        
        // Ensure uniqueness
        $sql = "SELECT COUNT(*) as count FROM products WHERE product_code = ?";
        $result = $this->db->fetchRow($sql, [$productCode]);
        
        if ($result['count'] > 0) {
            $productCode .= '-' . mt_rand(1000, 9999);
        }
        
        return $productCode;
    }
    
    /**
     * Generate QR Code for product
     */
    private function generateQRCode($productId) {
        try {
            // Get product details
            $sql = "SELECT uuid, product_name FROM products WHERE id = ?";
            $product = $this->db->fetchRow($sql, [$productId]);
            
            if (!$product) {
                throw new Exception("Product not found for QR generation");
            }
            
            // Check if QRCodeGenerator class exists
            if (class_exists('QRCodeGenerator')) {
                // Generate QR code
                $qrGenerator = new QRCodeGenerator();
                $qrResult = $qrGenerator->generateProductQR(
                    $productId, 
                    $product['uuid'], 
                    $product['product_name']
                );
                
                // Update product with QR code info
                $sql = "UPDATE products SET qr_code_path = ?, qr_code_data = ? WHERE id = ?";
                $this->db->execute($sql, [
                    $qrResult['file_path'], 
                    $qrResult['data'], 
                    $productId
                ]);
                
                return $qrResult;
            } else {
                // Fallback - store basic QR data without file
                $qrCodeData = json_encode([
                    'type' => 'product',
                    'product_id' => $productId,
                    'url' => "/product?id={$productId}",
                    'generated_at' => date('c'),
                    'error' => 'QR code generator not available'
                ]);
                
                $sql = "UPDATE products SET qr_code_data = ? WHERE id = ?";
                $this->db->execute($sql, [$qrCodeData, $productId]);
                
                return null;
            }
            
        } catch (Exception $e) {
            error_log("QR Code generation failed for product {$productId}: " . $e->getMessage());
            
            // Fallback - store basic QR data without file
            $qrCodeData = json_encode([
                'type' => 'product',
                'product_id' => $productId,
                'url' => "/product?id={$productId}",
                'generated_at' => date('c'),
                'error' => 'QR file generation failed'
            ]);
            
            $sql = "UPDATE products SET qr_code_data = ? WHERE id = ?";
            $this->db->execute($sql, [$qrCodeData, $productId]);
            
            return null;
        }
    }
    
    /**
     * Populate object properties from array
     */
    private function populateFromArray($data) {
        $this->id = $data['id'];
        $this->uuid = $data['uuid'];
        $this->companyId = $data['company_id'];
        $this->productName = $data['product_name'];
        $this->productCode = $data['product_code'];
        $this->barcode = $data['barcode'];
        $this->category = $data['category'];
        $this->status = $data['status'];
    }
    
    /**
     * Get all products with pagination
     */
    public function getAll($page = 1, $limit = 20, $filters = []) {
        $offset = ($page - 1) * $limit;
        
        $whereClause = "WHERE p.status = 'active'";
        $params = [];
        
        if (!empty($filters['company_id'])) {
            $whereClause .= " AND p.company_id = ?";
            $params[] = $filters['company_id'];
        }
        
        if (!empty($filters['category'])) {
            $whereClause .= " AND p.category = ?";
            $params[] = $filters['category'];
        }
        
        // Handle individual search parameters with more flexible matching
        if (!empty($filters['product_code'])) {
            // For product code, allow partial matching
            $whereClause .= " AND p.product_code LIKE ?";
            $params[] = '%' . $filters['product_code'] . '%';
        } elseif (!empty($filters['barcode'])) {
            // For barcode, allow partial matching
            $whereClause .= " AND p.barcode LIKE ?";
            $params[] = '%' . $filters['barcode'] . '%';
        } elseif (!empty($filters['search'])) {
            // For general search, search in multiple fields
            $whereClause .= " AND (p.product_name LIKE ? OR p.product_code LIKE ? OR p.barcode LIKE ? OR p.brand LIKE ? OR p.category LIKE ? OR c.company_name LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm; // product_name
            $params[] = $searchTerm; // product_code
            $params[] = $searchTerm; // barcode
            $params[] = $searchTerm; // brand
            $params[] = $searchTerm; // category
            $params[] = $searchTerm; // company_name
        }
        
        $sql = "SELECT p.*, c.company_name, c.company_type 
                FROM products p 
                JOIN companies c ON p.company_id = c.id 
                {$whereClause} 
                ORDER BY p.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $params[] = $limit;
        $params[] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get total count for pagination
     */
    public function getTotalCount($filters = []) {
        $whereClause = "WHERE p.status = 'active'";
        $params = [];
        
        if (!empty($filters['company_id'])) {
            $whereClause .= " AND p.company_id = ?";
            $params[] = $filters['company_id'];
        }
        
        if (!empty($filters['category'])) {
            $whereClause .= " AND p.category = ?";
            $params[] = $filters['category'];
        }
        
        // Handle individual search parameters with more flexible matching
        if (!empty($filters['product_code'])) {
            // For product code, allow partial matching
            $whereClause .= " AND p.product_code LIKE ?";
            $params[] = '%' . $filters['product_code'] . '%';
        } elseif (!empty($filters['barcode'])) {
            // For barcode, allow partial matching
            $whereClause .= " AND p.barcode LIKE ?";
            $params[] = '%' . $filters['barcode'] . '%';
        } elseif (!empty($filters['search'])) {
            // For general search, search in multiple fields
            $whereClause .= " AND (p.product_name LIKE ? OR p.product_code LIKE ? OR p.barcode LIKE ? OR p.brand LIKE ? OR p.category LIKE ? OR c.company_name LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm; // product_name
            $params[] = $searchTerm; // product_code
            $params[] = $searchTerm; // barcode
            $params[] = $searchTerm; // brand
            $params[] = $searchTerm; // category
            $params[] = $searchTerm; // company_name
        }
        
        $sql = "SELECT COUNT(*) as total FROM products p JOIN companies c ON p.company_id = c.id {$whereClause}";
        
        $result = $this->db->fetchRow($sql, $params);
        return $result['total'];
    }
    
    /**
     * Search products by QR code data
     */
    public function searchByQRCode($qrData) {
        $sql = "SELECT p.*, c.company_name, c.company_type 
                FROM products p 
                JOIN companies c ON p.company_id = c.id 
                WHERE p.qr_code_data LIKE ? AND p.status = 'active'";
        
        return $this->db->fetchAll($sql, ['%' . $qrData . '%']);
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getUuid() { return $this->uuid; }
    public function getCompanyId() { return $this->companyId; }
    public function getProductName() { return $this->productName; }
    public function getProductCode() { return $this->productCode; }
    public function getBarcode() { return $this->barcode; }
    public function getCategory() { return $this->category; }
    public function getStatus() { return $this->status; }
    
    /**
     * Check if product is active
     */
    public function isActive() { return $this->status === 'active'; }
    
    /**
     * Convert to array for JSON response
     */
    public function toArray($includeDetails = false) {
        $data = [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'product_name' => $this->productName,
            'product_code' => $this->productCode,
            'barcode' => $this->barcode,
            'category' => $this->category,
            'status' => $this->status
        ];
        
        if ($includeDetails) {
            // Add more detailed information
            $sql = "SELECT * FROM products WHERE id = ?";
            $details = $this->db->fetchRow($sql, [$this->id]);
            
            $data = array_merge($data, [
                'subcategory' => $details['subcategory'],
                'brand' => $details['brand'],
                'description' => $details['description'],
                'weight' => $details['weight'],
                'volume' => $details['volume'],
                'dimensions' => $details['dimensions'] ? json_decode($details['dimensions'], true) : null,
                'packaging_type' => $details['packaging_type'],
                'shelf_life' => $details['shelf_life'],
                'origin_country' => $details['origin_country'],
                'origin_region' => $details['origin_region'],
                'harvest_season' => $details['harvest_season'],
                'product_images' => $details['product_images'] ? json_decode($details['product_images'], true) : [],
                'documentation' => $details['documentation'],
                'qr_code_path' => $details['qr_code_path']
            ]);
        }
        
        return $data;
    }
}
?>