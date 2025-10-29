<?php
/**
 * Blockchain Integration Class
 * 
 * Handles blockchain interactions for immutable data storage
 * and smart contract operations
 */

require_once ROOT_DIR . '/vendor/autoload.php';

class BlockchainIntegration {
    private $config;
    private $web3;
    private $db;
    
    public function __construct() {
        $this->config = require CONFIG_DIR . '/blockchain.php';
        $this->db = Database::getInstance();
        
        // Initialize Web3 connection (placeholder for now)
        $this->initializeWeb3();
    }
    
    /**
     * Initialize Web3 connection
     */
    private function initializeWeb3() {
        try {
            // This would initialize actual Web3 connection
            // For now, we'll simulate blockchain operations
            $this->web3 = (object) [
                'connected' => true,
                'network' => $this->config['network'] ?? 'testnet',
                'provider' => $this->config['provider_url'] ?? 'http://localhost:8545'
            ];
            
        } catch (Exception $e) {
            error_log("Blockchain connection failed: " . $e->getMessage());
            $this->web3 = (object) ['connected' => false];
        }
    }
    
    /**
     * Record supply chain step on blockchain
     */
    public function recordSupplyChainStep($stepData) {
        try {
            // Prepare data for blockchain
            $blockchainData = $this->prepareSupplyChainData($stepData);
            
            // Calculate data hash for integrity verification
            $dataHash = hash('sha256', json_encode($blockchainData));
            
            // Simulate blockchain transaction
            $transactionResult = $this->simulateBlockchainTransaction([
                'type' => 'supply_chain_step',
                'data' => $blockchainData,
                'hash' => $dataHash
            ]);
            
            // Store blockchain record in database
            if ($transactionResult['success']) {
                $this->storeBlockchainRecord([
                    'record_type' => 'supply_chain_step',
                    'record_id' => $stepData['id'],
                    'transaction_hash' => $transactionResult['transaction_hash'],
                    'block_number' => $transactionResult['block_number'],
                    'data_hash' => $dataHash,
                    'gas_used' => $transactionResult['gas_used'] ?? 0,
                    'transaction_fee' => $transactionResult['transaction_fee'] ?? 0
                ]);
                
                return [
                    'success' => true,
                    'transaction_hash' => $transactionResult['transaction_hash'],
                    'block_number' => $transactionResult['block_number'],
                    'data_hash' => $dataHash
                ];
            } else {
                throw new Exception($transactionResult['error']);
            }
            
        } catch (Exception $e) {
            error_log("Blockchain recording failed: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Record validation result on blockchain
     */
    public function recordValidation($validationData) {
        try {
            $blockchainData = $this->prepareValidationData($validationData);
            $dataHash = hash('sha256', json_encode($blockchainData));
            
            $transactionResult = $this->simulateBlockchainTransaction([
                'type' => 'validation',
                'data' => $blockchainData,
                'hash' => $dataHash
            ]);
            
            if ($transactionResult['success']) {
                $this->storeBlockchainRecord([
                    'record_type' => 'validation',
                    'record_id' => $validationData['id'],
                    'transaction_hash' => $transactionResult['transaction_hash'],
                    'block_number' => $transactionResult['block_number'],
                    'data_hash' => $dataHash,
                    'gas_used' => $transactionResult['gas_used'] ?? 0,
                    'transaction_fee' => $transactionResult['transaction_fee'] ?? 0
                ]);
                
                return [
                    'success' => true,
                    'transaction_hash' => $transactionResult['transaction_hash'],
                    'block_number' => $transactionResult['block_number']
                ];
            } else {
                throw new Exception($transactionResult['error']);
            }
            
        } catch (Exception $e) {
            error_log("Validation blockchain recording failed: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Record impact score on blockchain
     */
    public function recordImpactScore($impactData) {
        try {
            $blockchainData = $this->prepareImpactData($impactData);
            $dataHash = hash('sha256', json_encode($blockchainData));
            
            $transactionResult = $this->simulateBlockchainTransaction([
                'type' => 'impact_score',
                'data' => $blockchainData,
                'hash' => $dataHash
            ]);
            
            if ($transactionResult['success']) {
                $this->storeBlockchainRecord([
                    'record_type' => 'impact_score',
                    'record_id' => $impactData['id'],
                    'transaction_hash' => $transactionResult['transaction_hash'],
                    'block_number' => $transactionResult['block_number'],
                    'data_hash' => $dataHash
                ]);
                
                return [
                    'success' => true,
                    'transaction_hash' => $transactionResult['transaction_hash']
                ];
            } else {
                throw new Exception($transactionResult['error']);
            }
            
        } catch (Exception $e) {
            error_log("Impact score blockchain recording failed: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Prepare supply chain data for blockchain
     */
    private function prepareSupplyChainData($stepData) {
        return [
            'step_id' => $stepData['id'],
            'product_batch_id' => $stepData['product_batch_id'],
            'company_id' => $stepData['company_id'],
            'step_type' => $stepData['step_type'],
            'step_name' => $stepData['step_name'],
            'location' => $stepData['location_coordinates'] ?? null,
            'start_date' => $stepData['start_date'],
            'end_date' => $stepData['end_date'],
            'environmental_data' => [
                'carbon_emissions' => $stepData['carbon_emissions'],
                'water_usage' => $stepData['water_usage'],
                'energy_consumption' => $stepData['energy_consumption'],
                'waste_generated' => $stepData['waste_generated']
            ],
            'social_data' => [
                'worker_count' => $stepData['worker_count'],
                'average_wage' => $stepData['average_wage'],
                'working_hours' => $stepData['working_hours_per_day'],
                'safety_incidents' => $stepData['safety_incidents']
            ],
            'timestamp' => time(),
            'version' => '1.0'
        ];
    }
    
    /**
     * Prepare validation data for blockchain
     */
    private function prepareValidationData($validationData) {
        return [
            'validation_id' => $validationData['id'],
            'supply_chain_step_id' => $validationData['supply_chain_step_id'],
            'validator_id' => $validationData['validator_id'],
            'validation_type' => $validationData['validation_type'],
            'validation_result' => $validationData['validation_result'],
            'confidence_score' => $validationData['confidence_score'],
            'completed_at' => $validationData['completed_at'],
            'timestamp' => time(),
            'version' => '1.0'
        ];
    }
    
    /**
     * Prepare impact data for blockchain
     */
    private function prepareImpactData($impactData) {
        return [
            'impact_id' => $impactData['id'],
            'product_batch_id' => $impactData['product_batch_id'],
            'overall_score' => $impactData['overall_score'],
            'overall_grade' => $impactData['overall_grade'],
            'environmental_score' => $impactData['environmental_score'],
            'social_score' => $impactData['social_score'],
            'transparency_score' => $impactData['transparency_score'],
            'calculation_date' => $impactData['calculation_date'],
            'total_carbon_footprint' => $impactData['total_carbon_footprint'],
            'total_water_footprint' => $impactData['total_water_footprint'],
            'timestamp' => time(),
            'version' => '1.0'
        ];
    }
    
    /**
     * Simulate blockchain transaction (placeholder for real implementation)
     */
    private function simulateBlockchainTransaction($transactionData) {
        // In a real implementation, this would interact with actual blockchain
        // For now, we simulate the transaction
        
        if (!$this->web3->connected) {
            return ['success' => false, 'error' => 'Blockchain not connected'];
        }
        
        // Simulate transaction processing time
        usleep(100000); // 0.1 second delay
        
        // Generate mock transaction details
        $transactionHash = '0x' . bin2hex(random_bytes(32));
        $blockNumber = time() + rand(1000, 9999); // Mock block number
        $gasUsed = rand(21000, 100000);
        $gasPrice = 20000000000; // 20 Gwei
        $transactionFee = ($gasUsed * $gasPrice) / 1000000000000000000; // Convert to ETH
        
        return [
            'success' => true,
            'transaction_hash' => $transactionHash,
            'block_number' => $blockNumber,
            'gas_used' => $gasUsed,
            'gas_price' => $gasPrice,
            'transaction_fee' => $transactionFee
        ];
    }
    
    /**
     * Store blockchain record in database
     */
    private function storeBlockchainRecord($recordData) {
        $uuid = $this->db->generateUUID();
        
        $sql = "INSERT INTO blockchain_records (uuid, transaction_hash, block_number, 
                record_type, record_id, data_hash, gas_used, transaction_fee, 
                confirmation_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'confirmed')";
        
        $params = [
            $uuid,
            $recordData['transaction_hash'],
            $recordData['block_number'],
            $recordData['record_type'],
            $recordData['record_id'],
            $recordData['data_hash'],
            $recordData['gas_used'] ?? 0,
            $recordData['transaction_fee'] ?? 0
        ];
        
        return $this->db->insert($sql, $params);
    }
    
    /**
     * Verify data integrity using blockchain
     */
    public function verifyDataIntegrity($recordType, $recordId, $currentData) {
        try {
            // Get blockchain record
            $sql = "SELECT transaction_hash, data_hash FROM blockchain_records 
                    WHERE record_type = ? AND record_id = ? 
                    ORDER BY created_at DESC LIMIT 1";
            
            $blockchainRecord = $this->db->fetchRow($sql, [$recordType, $recordId]);
            
            if (!$blockchainRecord) {
                return ['verified' => false, 'error' => 'No blockchain record found'];
            }
            
            // Calculate current data hash
            $currentDataHash = hash('sha256', json_encode($currentData));
            
            // Compare hashes
            if ($currentDataHash === $blockchainRecord['data_hash']) {
                return [
                    'verified' => true,
                    'transaction_hash' => $blockchainRecord['transaction_hash'],
                    'data_hash' => $currentDataHash
                ];
            } else {
                return [
                    'verified' => false,
                    'error' => 'Data integrity check failed - data may have been tampered with',
                    'stored_hash' => $blockchainRecord['data_hash'],
                    'current_hash' => $currentDataHash
                ];
            }
            
        } catch (Exception $e) {
            error_log("Data integrity verification failed: " . $e->getMessage());
            return ['verified' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Get blockchain transaction details
     */
    public function getTransactionDetails($transactionHash) {
        try {
            $sql = "SELECT * FROM blockchain_records WHERE transaction_hash = ?";
            $record = $this->db->fetchRow($sql, [$transactionHash]);
            
            if (!$record) {
                return ['found' => false];
            }
            
            return [
                'found' => true,
                'transaction_hash' => $record['transaction_hash'],
                'block_number' => $record['block_number'],
                'record_type' => $record['record_type'],
                'record_id' => $record['record_id'],
                'gas_used' => $record['gas_used'],
                'transaction_fee' => $record['transaction_fee'],
                'confirmation_status' => $record['confirmation_status'],
                'created_at' => $record['created_at']
            ];
            
        } catch (Exception $e) {
            error_log("Error getting transaction details: " . $e->getMessage());
            return ['found' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Get blockchain statistics
     */
    public function getBlockchainStats() {
        try {
            $stats = [];
            
            // Total transactions
            $sql = "SELECT COUNT(*) as total FROM blockchain_records";
            $result = $this->db->fetchRow($sql);
            $stats['total_transactions'] = $result['total'];
            
            // Transactions by type
            $sql = "SELECT record_type, COUNT(*) as count FROM blockchain_records GROUP BY record_type";
            $results = $this->db->fetchAll($sql);
            $stats['transactions_by_type'] = [];
            foreach ($results as $row) {
                $stats['transactions_by_type'][$row['record_type']] = $row['count'];
            }
            
            // Total gas used
            $sql = "SELECT SUM(gas_used) as total_gas FROM blockchain_records";
            $result = $this->db->fetchRow($sql);
            $stats['total_gas_used'] = $result['total_gas'] ?? 0;
            
            // Total transaction fees
            $sql = "SELECT SUM(transaction_fee) as total_fees FROM blockchain_records";
            $result = $this->db->fetchRow($sql);
            $stats['total_transaction_fees'] = $result['total_fees'] ?? 0;
            
            // Network status
            $stats['network_status'] = [
                'connected' => $this->web3->connected,
                'network' => $this->web3->network ?? 'unknown',
                'provider' => $this->web3->provider ?? 'unknown'
            ];
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Error getting blockchain stats: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
    
    /**
     * Check blockchain connection status
     */
    public function isConnected() {
        return $this->web3->connected ?? false;
    }
    
    /**
     * Get network information
     */
    public function getNetworkInfo() {
        return [
            'connected' => $this->isConnected(),
            'network' => $this->web3->network ?? 'unknown',
            'provider' => $this->web3->provider ?? 'unknown',
            'config' => $this->config
        ];
    }
}
?>