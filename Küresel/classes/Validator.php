<?php
/**
 * Validator Model Class
 * 
 * Handles validator management operations including registration,
 * validation processes, and reputation management
 */

class Validator {
    private $db;
    private $id;
    private $uuid;
    private $userId;
    private $validatorName;
    private $organizationType;
    private $reputationScore;
    private $status;
    private $experienceYears;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Create a new validator
     */
    public function create($data) {
        // Validate required fields
        $required = ['user_id', 'validator_name', 'organization_type'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Field '{$field}' is required");
            }
        }
        
        // Validate organization type
        $validTypes = ['ngo', 'certification_body', 'audit_firm', 'government', 'independent'];
        if (!in_array($data['organization_type'], $validTypes)) {
            throw new Exception("Invalid organization type");
        }
        
        // Generate UUID
        $uuid = $this->db->generateUUID();
        
        $sql = "INSERT INTO validators (uuid, user_id, validator_name, organization_type, 
                specialization, credentials, service_regions, token_balance, stake_amount, 
                blockchain_address, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $uuid,
            $data['user_id'],
            $data['validator_name'],
            $data['organization_type'],
            isset($data['specialization']) ? json_encode($data['specialization']) : null,
            isset($data['credentials']) ? json_encode($data['credentials']) : null,
            isset($data['service_regions']) ? json_encode($data['service_regions']) : null,
            $data['token_balance'] ?? 0.00000000,
            $data['stake_amount'] ?? 0.00000000,
            $data['blockchain_address'] ?? null,
            $data['status'] ?? 'under_review'
        ];
        
        $validatorId = $this->db->insert($sql, $params);
        
        // Load the created validator
        $this->loadById($validatorId);
        
        return $validatorId;
    }
    
    /**
     * Load validator by ID
     */
    public function loadById($id) {
        $sql = "SELECT v.*, u.email, u.first_name, u.last_name 
                FROM validators v 
                JOIN users u ON v.user_id = u.id 
                WHERE v.id = ?";
        $validator = $this->db->fetchRow($sql, [$id]);
        
        if (!$validator) {
            throw new Exception("Validator not found");
        }
        
        $this->populateFromArray($validator);
        return $this;
    }
    
    /**
     * Load validator by UUID
     */
    public function loadByUuid($uuid) {
        $sql = "SELECT v.*, u.email, u.first_name, u.last_name 
                FROM validators v 
                JOIN users u ON v.user_id = u.id 
                WHERE v.uuid = ?";
        $validator = $this->db->fetchRow($sql, [$uuid]);
        
        if (!$validator) {
            throw new Exception("Validator not found");
        }
        
        $this->populateFromArray($validator);
        return $this;
    }
    
    /**
     * Load validator by user ID
     */
    public function loadByUserId($userId) {
        $sql = "SELECT v.*, u.email, u.first_name, u.last_name 
                FROM validators v 
                JOIN users u ON v.user_id = u.id 
                WHERE v.user_id = ?";
        $validator = $this->db->fetchRow($sql, [$userId]);
        
        if (!$validator) {
            throw new Exception("Validator not found for this user");
        }
        
        $this->populateFromArray($validator);
        return $this;
    }
    
    /**
     * Update validator profile
     */
    public function update($data) {
        if (!$this->id) {
            throw new Exception("Validator not loaded");
        }
        
        $allowedFields = [
            'validator_name', 'organization_type', 'blockchain_address', 'status', 'experience_years'
        ];
        
        $updateFields = [];
        $params = [];
        
        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $updateFields[] = "{$field} = ?";
                $params[] = $value;
            } elseif ($field === 'specialization' || $field === 'credentials' || $field === 'service_regions') {
                $updateFields[] = "{$field} = ?";
                $params[] = json_encode($value);
            }
        }
        
        if (empty($updateFields)) {
            throw new Exception("No valid fields to update");
        }
        
        $params[] = $this->id;
        
        $sql = "UPDATE validators SET " . implode(', ', $updateFields) . 
               ", updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        
        $this->db->execute($sql, $params);
        
        // Reload validator data
        $this->loadById($this->id);
        
        return true;
    }
    
    /**
     * Create a validation record
     */
    public function createValidation($data) {
        if (!$this->id) {
            throw new Exception("Validator not loaded");
        }
        
        // Validate required fields
        $required = ['supply_chain_step_id', 'validation_type'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Field '{$field}' is required for validation");
            }
        }
        
        // Check if validator can validate this step (basic check)
        $sql = "SELECT scs.*, pb.product_id, p.company_id 
                FROM supply_chain_steps scs
                JOIN product_batches pb ON scs.product_batch_id = pb.id
                JOIN products p ON pb.product_id = p.id
                WHERE scs.id = ?";
        
        $step = $this->db->fetchRow($sql, [$data['supply_chain_step_id']]);
        
        if (!$step) {
            throw new Exception("Supply chain step not found");
        }
        
        // Generate UUID for validation
        $uuid = $this->db->generateUUID();
        
        $sql = "INSERT INTO validation_records (uuid, supply_chain_step_id, validator_id, 
                validation_type, validation_method, validation_criteria, validation_result, 
                confidence_score, findings, recommendations, evidence_documents, 
                evidence_photos, validation_fee, reward_amount, stake_amount, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $uuid,
            $data['supply_chain_step_id'],
            $this->id,
            $data['validation_type'],
            $data['validation_method'] ?? null,
            isset($data['validation_criteria']) ? json_encode($data['validation_criteria']) : null,
            $data['validation_result'] ?? 'pending',
            $data['confidence_score'] ?? null,
            $data['findings'] ?? null,
            $data['recommendations'] ?? null,
            isset($data['evidence_documents']) ? json_encode($data['evidence_documents']) : null,
            isset($data['evidence_photos']) ? json_encode($data['evidence_photos']) : null,
            $data['validation_fee'] ?? 0.00000000,
            $data['reward_amount'] ?? 0.00000000,
            $data['stake_amount'] ?? 0.00000000,
            'pending'
        ];
        
        $validationId = $this->db->insert($sql, $params);
        
        return $validationId;
    }
    
    /**
     * Complete a validation
     */
    public function completeValidation($validationId, $result, $findings, $evidence = []) {
        if (!$this->id) {
            throw new Exception("Validator not loaded");
        }
        
        // Validate result
        $validResults = ['approved', 'rejected', 'needs_clarification'];
        if (!in_array($result, $validResults)) {
            throw new Exception("Invalid validation result");
        }
        
        // Update validation record
        $sql = "UPDATE validation_records SET 
                validation_result = ?, 
                findings = ?, 
                evidence_documents = ?,
                completed_at = CURRENT_TIMESTAMP,
                status = 'completed',
                updated_at = CURRENT_TIMESTAMP
                WHERE id = ? AND validator_id = ?";
        
        $params = [
            $result,
            $findings,
            json_encode($evidence),
            $validationId,
            $this->id
        ];
        
        $affected = $this->db->execute($sql, $params);
        
        if ($affected === 0) {
            throw new Exception("Validation not found or not assigned to this validator");
        }
        
        // Update supply chain step validation status
        $stepSql = "UPDATE supply_chain_steps scs
                    JOIN validation_records vr ON scs.id = vr.supply_chain_step_id
                    SET scs.validation_status = ?
                    WHERE vr.id = ?";
        
        $stepStatus = ($result === 'approved') ? 'validated' : 'rejected';
        $this->db->execute($stepSql, [$stepStatus, $validationId]);
        
        // Update validator statistics
        $this->updateValidationStats($result === 'approved');
        
        return true;
    }
    
    /**
     * Get validator's validation history
     */
    public function getValidationHistory($page = 1, $limit = 20, $status = null) {
        if (!$this->id) {
            throw new Exception("Validator not loaded");
        }
        
        $offset = ($page - 1) * $limit;
        
        $whereClause = "WHERE vr.validator_id = ?";
        $params = [$this->id];
        
        if ($status) {
            $whereClause .= " AND vr.status = ?";
            $params[] = $status;
        }
        
        $sql = "SELECT vr.*, scs.step_name, scs.step_type, 
                       p.product_name, c.company_name
                FROM validation_records vr
                JOIN supply_chain_steps scs ON vr.supply_chain_step_id = scs.id
                JOIN product_batches pb ON scs.product_batch_id = pb.id
                JOIN products p ON pb.product_id = p.id
                JOIN companies c ON scs.company_id = c.id
                {$whereClause}
                ORDER BY vr.requested_at DESC
                LIMIT ? OFFSET ?";
        
        $params[] = $limit;
        $params[] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get pending validations for this validator
     */
    public function getPendingValidations($page = 1, $limit = 20) {
        if (!$this->id) {
            throw new Exception("Validator not loaded");
        }
        
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT vr.*, scs.step_name, scs.step_type, 
                       p.product_name, c.company_name
                FROM validation_records vr
                JOIN supply_chain_steps scs ON vr.supply_chain_step_id = scs.id
                JOIN product_batches pb ON scs.product_batch_id = pb.id
                JOIN products p ON pb.product_id = p.id
                JOIN companies c ON scs.company_id = c.id
                WHERE vr.validator_id = ? AND vr.status = 'pending'
                ORDER BY vr.requested_at ASC
                LIMIT ? OFFSET ?";
        
        return $this->db->fetchAll($sql, [$this->id, $limit, $offset]);
    }
    
    /**
     * Get validator statistics
     */
    public function getStatistics() {
        if (!$this->id) {
            throw new Exception("Validator not loaded");
        }
        
        $stats = [];
        
        // Total validations
        $sql = "SELECT COUNT(*) as total FROM validation_records WHERE validator_id = ?";
        $result = $this->db->fetchRow($sql, [$this->id]);
        $stats['total_validations'] = $result['total'];
        
        // Completed validations
        $sql = "SELECT COUNT(*) as total FROM validation_records WHERE validator_id = ? AND status = 'completed'";
        $result = $this->db->fetchRow($sql, [$this->id]);
        $stats['completed_validations'] = $result['total'];
        
        // Success rate
        $sql = "SELECT 
                    COUNT(*) as total_completed,
                    SUM(CASE WHEN validation_result = 'approved' THEN 1 ELSE 0 END) as approved
                FROM validation_records 
                WHERE validator_id = ? AND status = 'completed'";
        $result = $this->db->fetchRow($sql, [$this->id]);
        
        if ($result['total_completed'] > 0) {
            $stats['success_rate'] = round(($result['approved'] / $result['total_completed']) * 100, 2);
        } else {
            $stats['success_rate'] = 0;
        }
        
        // Average response time
        $sql = "SELECT AVG(response_time_hours) as avg_response_time 
                FROM validation_records 
                WHERE validator_id = ? AND response_time_hours IS NOT NULL";
        $result = $this->db->fetchRow($sql, [$this->id]);
        $stats['avg_response_time'] = round($result['avg_response_time'] ?? 0, 2);
        
        return $stats;
    }
    
    /**
     * Update validation statistics
     */
    private function updateValidationStats($successful) {
        $sql = "UPDATE validators SET 
                total_validations = total_validations + 1,
                successful_validations = successful_validations + ?,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = ?";
        
        $this->db->execute($sql, [$successful ? 1 : 0, $this->id]);
        
        // Recalculate reputation score
        $this->recalculateReputationScore();
    }
    
    /**
     * Recalculate reputation score based on performance
     */
    private function recalculateReputationScore() {
        $stats = $this->getStatistics();
        
        $baseScore = 50; // Starting score
        $completionBonus = min($stats['completed_validations'] * 2, 30); // Max 30 points for completion
        $successBonus = ($stats['success_rate'] / 100) * 20; // Max 20 points for success rate
        
        $reputationScore = $baseScore + $completionBonus + $successBonus;
        $reputationScore = min($reputationScore, 100); // Cap at 100
        
        $sql = "UPDATE validators SET reputation_score = ? WHERE id = ?";
        $this->db->execute($sql, [$reputationScore, $this->id]);
        
        $this->reputationScore = $reputationScore;
    }
    
    /**
     * Update token balance
     */
    public function updateTokenBalance($amount, $operation = 'add') {
        if (!$this->id) {
            throw new Exception("Validator not loaded");
        }
        
        $operator = ($operation === 'add') ? '+' : '-';
        
        $sql = "UPDATE validators SET 
                token_balance = token_balance {$operator} ?,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = ?";
        
        $this->db->execute($sql, [$amount, $this->id]);
        
        return true;
    }
    
    /**
     * Populate object properties from array
     */
    private function populateFromArray($data) {
        $this->id = $data['id'];
        $this->uuid = $data['uuid'];
        $this->userId = $data['user_id'];
        $this->validatorName = $data['validator_name'];
        $this->organizationType = $data['organization_type'];
        $this->reputationScore = $data['reputation_score'];
        $this->status = $data['status'];
        $this->experienceYears = $data['experience_years'] ?? 0;
    }
    
    /**
     * Get all validators with pagination
     */
    public function getAll($page = 1, $limit = 20, $filters = []) {
        $offset = ($page - 1) * $limit;
        
        $whereClause = "WHERE v.status = 'active'";
        $params = [];
        
        if (!empty($filters['organization_type'])) {
            $whereClause .= " AND v.organization_type = ?";
            $params[] = $filters['organization_type'];
        }
        
        if (!empty($filters['specialization'])) {
            $whereClause .= " AND JSON_CONTAINS(v.specialization, ?)";
            $params[] = json_encode($filters['specialization']);
        }
        
        if (!empty($filters['search'])) {
            $whereClause .= " AND v.validator_name LIKE ?";
            $params[] = '%' . $filters['search'] . '%';
        }
        
        $sql = "SELECT v.*, u.email, u.first_name, u.last_name 
                FROM validators v 
                JOIN users u ON v.user_id = u.id 
                {$whereClause} 
                ORDER BY v.reputation_score DESC, v.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $params[] = $limit;
        $params[] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get total count for pagination
     */
    public function getTotalCount($filters = []) {
        $whereClause = "WHERE status = 'active'";
        $params = [];
        
        if (!empty($filters['organization_type'])) {
            $whereClause .= " AND organization_type = ?";
            $params[] = $filters['organization_type'];
        }
        
        if (!empty($filters['specialization'])) {
            $whereClause .= " AND JSON_CONTAINS(specialization, ?)";
            $params[] = json_encode($filters['specialization']);
        }
        
        if (!empty($filters['search'])) {
            $whereClause .= " AND validator_name LIKE ?";
            $params[] = '%' . $filters['search'] . '%';
        }
        
        $sql = "SELECT COUNT(*) as total FROM validators {$whereClause}";
        
        $result = $this->db->fetchRow($sql, $params);
        return $result['total'];
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getUuid() { return $this->uuid; }
    public function getUserId() { return $this->userId; }
    public function getValidatorName() { return $this->validatorName; }
    public function getOrganizationType() { return $this->organizationType; }
    public function getReputationScore() { return $this->reputationScore; }
    public function getStatus() { return $this->status; }
    public function getExperienceYears() { return $this->experienceYears; }
    
    /**
     * Check if validator is active
     */
    public function isActive() { return $this->status === 'active'; }
    
    /**
     * Convert to array for JSON response
     */
    public function toArray($includeDetails = false) {
        $data = [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'validator_name' => $this->validatorName,
            'organization_type' => $this->organizationType,
            'reputation_score' => $this->reputationScore,
            'status' => $this->status,
            'experience_years' => $this->experienceYears
        ];
        
        if ($includeDetails) {
            // Add more detailed information
            $sql = "SELECT * FROM validators WHERE id = ?";
            $details = $this->db->fetchRow($sql, [$this->id]);
            
            $data = array_merge($data, [
                'specialization' => $details['specialization'] ? json_decode($details['specialization'], true) : [],
                'credentials' => $details['credentials'] ? json_decode($details['credentials'], true) : [],
                'service_regions' => $details['service_regions'] ? json_decode($details['service_regions'], true) : [],
                'total_validations' => $details['total_validations'],
                'successful_validations' => $details['successful_validations'],
                'average_response_time' => $details['average_response_time'],
                'token_balance' => $details['token_balance']
            ]);
        }
        
        return $data;
    }
}
?>