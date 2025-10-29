<?php
/**
 * Company Model Class
 * 
 * Handles company management operations including registration,
 * profile management, and supply chain participation
 */

class Company {
    private $db;
    private $id;
    private $uuid;
    private $userId;
    private $companyName;
    private $companyType;
    private $transparencyScore;
    private $reputationScore;
    private $status;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new company
     */
    public function create($data) {
        // Validate required fields
        $required = ['user_id', 'company_name', 'company_type'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Field '{$field}' is required");
            }
        }
        
        // Validate company type
        $validTypes = ['supplier', 'manufacturer', 'distributor', 'retailer', 'farmer', 'fisher', 'logistics'];
        if (!in_array($data['company_type'], $validTypes)) {
            throw new Exception("Invalid company type");
        }
        
        // Generate UUID
        $uuid = $this->db->generateUUID();
        
        $sql = "INSERT INTO companies (uuid, user_id, company_name, legal_name, tax_number, 
                industry_sector, company_type, registration_number, website, description,
                address_line1, address_line2, city, state, postal_code, country,
                contact_person, contact_email, contact_phone, certifications, 
                compliance_documents, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $uuid,
            $data['user_id'],
            $data['company_name'],
            $data['legal_name'] ?? null,
            $data['tax_number'] ?? null,
            $data['industry_sector'] ?? null,
            $data['company_type'],
            $data['registration_number'] ?? null,
            $data['website'] ?? null,
            $data['description'] ?? null,
            $data['address_line1'] ?? null,
            $data['address_line2'] ?? null,
            $data['city'] ?? null,
            $data['state'] ?? null,
            $data['postal_code'] ?? null,
            $data['country'] ?? null,
            $data['contact_person'] ?? null,
            $data['contact_email'] ?? null,
            $data['contact_phone'] ?? null,
            isset($data['certifications']) ? json_encode($data['certifications']) : null,
            isset($data['compliance_documents']) ? json_encode($data['compliance_documents']) : null,
            $data['status'] ?? 'under_review'
        ];
        
        $companyId = $this->db->insert($sql, $params);
        
        // Load the created company
        $this->loadById($companyId);
        
        return $companyId;
    }
    
    /**
     * Load company by ID
     */
    public function loadById($id) {
        $sql = "SELECT c.*, u.email, u.first_name, u.last_name 
                FROM companies c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.id = ?";
        $company = $this->db->fetchRow($sql, [$id]);
        
        if (!$company) {
            throw new Exception("Company not found");
        }
        
        $this->populateFromArray($company);
        return $this;
    }
    
    /**
     * Load company by UUID
     */
    public function loadByUuid($uuid) {
        $sql = "SELECT c.*, u.email, u.first_name, u.last_name 
                FROM companies c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.uuid = ?";
        $company = $this->db->fetchRow($sql, [$uuid]);
        
        if (!$company) {
            throw new Exception("Company not found");
        }
        
        $this->populateFromArray($company);
        return $this;
    }
    
    /**
     * Load company by user ID
     */
    public function loadByUserId($userId) {
        $sql = "SELECT c.*, u.email, u.first_name, u.last_name 
                FROM companies c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.user_id = ?";
        $company = $this->db->fetchRow($sql, [$userId]);
        
        if (!$company) {
            throw new Exception("Company not found for this user");
        }
        
        $this->populateFromArray($company);
        return $this;
    }
    
    /**
     * Update company profile
     */
    public function update($data) {
        if (!$this->id) {
            throw new Exception("Company not loaded");
        }
        
        $allowedFields = [
            'company_name', 'legal_name', 'tax_number', 'industry_sector', 
            'company_type', 'registration_number', 'website', 'description',
            'address_line1', 'address_line2', 'city', 'state', 'postal_code', 'country',
            'contact_person', 'contact_email', 'contact_phone', 'status'
        ];
        
        $updateFields = [];
        $params = [];
        
        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                if ($field === 'certifications' || $field === 'compliance_documents') {
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
        
        $sql = "UPDATE companies SET " . implode(', ', $updateFields) . 
               ", updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        
        $this->db->execute($sql, $params);
        
        // Reload company data
        $this->loadById($this->id);
        
        return true;
    }
    
    /**
     * Update transparency score
     */
    public function updateTransparencyScore($score) {
        if (!$this->id) {
            throw new Exception("Company not loaded");
        }
        
        if ($score < 0 || $score > 100) {
            throw new Exception("Score must be between 0 and 100");
        }
        
        $sql = "UPDATE companies SET transparency_score = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $this->db->execute($sql, [$score, $this->id]);
        
        $this->transparencyScore = $score;
        
        return true;
    }
    
    /**
     * Update reputation score
     */
    public function updateReputationScore($score) {
        if (!$this->id) {
            throw new Exception("Company not loaded");
        }
        
        if ($score < 0 || $score > 100) {
            throw new Exception("Score must be between 0 and 100");
        }
        
        $sql = "UPDATE companies SET reputation_score = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $this->db->execute($sql, [$score, $this->id]);
        
        $this->reputationScore = $score;
        
        return true;
    }
    
    /**
     * Add certification
     */
    public function addCertification($certification) {
        if (!$this->id) {
            throw new Exception("Company not loaded");
        }
        
        // Get current certifications
        $sql = "SELECT certifications FROM companies WHERE id = ?";
        $result = $this->db->fetchRow($sql, [$this->id]);
        
        $certifications = $result['certifications'] ? json_decode($result['certifications'], true) : [];
        $certifications[] = array_merge($certification, ['added_at' => date('Y-m-d H:i:s')]);
        
        $sql = "UPDATE companies SET certifications = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $this->db->execute($sql, [json_encode($certifications), $this->id]);
        
        return true;
    }
    
    /**
     * Get products for this company
     */
    public function getProducts($page = 1, $limit = 10, $filters = []) {
        if (!$this->id) {
            throw new Exception("Company not loaded");
        }
        
        $offset = ($page - 1) * $limit;
        
        $whereClause = "WHERE p.company_id = ? AND p.status = 'active'";
        $params = [$this->id];
        
        // Add filters
        if (!empty($filters['category'])) {
            $whereClause .= " AND p.category = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['search'])) {
            $whereClause .= " AND (p.product_name LIKE ? OR p.product_code LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql = "SELECT p.*, c.company_name 
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
     * Get recent products for this company
     */
    public function getRecentProducts($limit = 10) {
        if (!$this->id) {
            throw new Exception("Company not loaded");
        }
        
        $sql = "SELECT * FROM products 
                WHERE company_id = ? 
                ORDER BY created_at DESC 
                LIMIT ?";
        return $this->db->fetchAll($sql, [$this->id, $limit]);
    }
    
    /**
     * Get all company products without pagination
     */
    public function getAllProducts() {
        if (!$this->id) {
            throw new Exception("Company not loaded");
        }
        
        $sql = "SELECT * FROM products WHERE company_id = ? AND status = 'active' 
                ORDER BY created_at DESC";
        
        $products = $this->db->fetchAll($sql, [$this->id]);
        
        // Decode product_images for each product
        foreach ($products as &$product) {
            if (!empty($product['product_images'])) {
                $product['product_images'] = json_decode($product['product_images'], true);
            } else {
                $product['product_images'] = [];
            }
        }
        
        return $products;
    }
    
    /**
     * Get company statistics
     */
    public function getStatistics() {
        if (!$this->id) {
            throw new Exception("Company not loaded");
        }
        
        $stats = [];
        
        // Total products
        $sql = "SELECT COUNT(*) as total FROM products WHERE company_id = ? AND status = 'active'";
        $result = $this->db->fetchRow($sql, [$this->id]);
        $stats['total_products'] = $result['total'];
        
        // Total batches
        $sql = "SELECT COUNT(*) as total FROM product_batches pb 
                JOIN products p ON pb.product_id = p.id 
                WHERE p.company_id = ?";
        $result = $this->db->fetchRow($sql, [$this->id]);
        $stats['total_batches'] = $result['total'];
        
        // Total supply chain steps
        $sql = "SELECT COUNT(*) as total FROM supply_chain_steps WHERE company_id = ?";
        $result = $this->db->fetchRow($sql, [$this->id]);
        $stats['total_supply_chain_steps'] = $result['total'];
        
        // Validated steps percentage
        $sql = "SELECT 
                    COUNT(*) as total_steps,
                    SUM(CASE WHEN validation_status = 'validated' THEN 1 ELSE 0 END) as validated_steps
                FROM supply_chain_steps WHERE company_id = ?";
        $result = $this->db->fetchRow($sql, [$this->id]);
        
        if ($result['total_steps'] > 0) {
            $stats['validation_percentage'] = round(($result['validated_steps'] / $result['total_steps']) * 100, 2);
        } else {
            $stats['validation_percentage'] = 0;
        }
        
        return $stats;
    }
    
    /**
     * Populate object properties from array
     */
    private function populateFromArray($data) {
        $this->id = $data['id'] ?? null;
        $this->uuid = $data['uuid'] ?? null;
        $this->userId = $data['user_id'] ?? null;
        $this->companyName = $data['company_name'] ?? null;
        $this->companyType = $data['company_type'] ?? null;
        $this->transparencyScore = $data['transparency_score'] ?? 0;
        $this->reputationScore = $data['reputation_score'] ?? 0;
        $this->status = $data['status'] ?? null;
    }
    
    /**
     * Get all companies with pagination
     */
    public function getAll($page = 1, $limit = 20, $filters = []) {
        $offset = ($page - 1) * $limit;
        
        $whereClause = "WHERE 1=1";
        $params = [];
        
        if (!empty($filters['company_type'])) {
            $whereClause .= " AND c.company_type = ?";
            $params[] = $filters['company_type'];
        }
        
        if (!empty($filters['status'])) {
            $whereClause .= " AND c.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['industry_sector'])) {
            $whereClause .= " AND c.industry_sector = ?";
            $params[] = $filters['industry_sector'];
        }
        
        if (!empty($filters['search'])) {
            $whereClause .= " AND (c.company_name LIKE ? OR c.legal_name LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql = "SELECT c.*, u.email, u.first_name, u.last_name 
                FROM companies c 
                JOIN users u ON c.user_id = u.id 
                {$whereClause} 
                ORDER BY c.transparency_score DESC, c.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $params[] = $limit;
        $params[] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get total count for pagination
     */
    public function getTotalCount($filters = []) {
        $whereClause = "WHERE 1=1";
        $params = [];
        
        if (!empty($filters['company_type'])) {
            $whereClause .= " AND company_type = ?";
            $params[] = $filters['company_type'];
        }
        
        if (!empty($filters['status'])) {
            $whereClause .= " AND status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['industry_sector'])) {
            $whereClause .= " AND industry_sector = ?";
            $params[] = $filters['industry_sector'];
        }
        
        if (!empty($filters['search'])) {
            $whereClause .= " AND (company_name LIKE ? OR legal_name LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql = "SELECT COUNT(*) as total FROM companies {$whereClause}";
        
        $result = $this->db->fetchRow($sql, $params);
        return $result['total'];
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getUuid() { return $this->uuid; }
    public function getUserId() { return $this->userId; }
    public function getCompanyName() { return $this->companyName; }
    public function getCompanyType() { return $this->companyType; }
    public function getTransparencyScore() { return $this->transparencyScore; }
    public function getReputationScore() { return $this->reputationScore; }
    public function getStatus() { return $this->status; }
    
    /**
     * Check if company is active
     */
    public function isActive() { return $this->status === 'active'; }
    
    /**
     * Convert to array for JSON response
     */
    public function toArray($includeDetails = false) {
        $data = [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'company_name' => $this->companyName,
            'company_type' => $this->companyType,
            'transparency_score' => $this->transparencyScore,
            'reputation_score' => $this->reputationScore,
            'status' => $this->status
        ];
        
        if ($includeDetails) {
            // Add more detailed information if needed
            $sql = "SELECT * FROM companies WHERE id = ?";
            $details = $this->db->fetchRow($sql, [$this->id]);
            
            $data = array_merge($data, [
                'legal_name' => $details['legal_name'],
                'industry_sector' => $details['industry_sector'],
                'website' => $details['website'],
                'description' => $details['description'],
                'country' => $details['country'],
                'certifications' => $details['certifications'] ? json_decode($details['certifications'], true) : [],
                'total_products' => $details['total_products'],
                'verified_data_percentage' => $details['verified_data_percentage'],
                // Add address fields
                'address_line1' => $details['address_line1'],
                'address_line2' => $details['address_line2'],
                'city' => $details['city'],
                'state' => $details['state'],
                'postal_code' => $details['postal_code'],
                'country' => $details['country'],
                // Add created_at field
                'created_at' => $details['created_at']
            ]);
        }
        
        return $data;
    }
}
?>