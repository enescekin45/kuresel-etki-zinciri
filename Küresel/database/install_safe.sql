-- Global Impact Chain Database Schema - Safe Installation
-- Küresel Etki Zinciri Veritabanı Şeması - Güvenli Kurulum
-- Created: 2025-10-08
-- Version: 1.0.1

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS kuresel_etki_zinciri 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE kuresel_etki_zinciri;

-- Check if this is a fresh installation
SET @fresh_install = (SELECT COUNT(*) = 0 FROM information_schema.tables WHERE table_schema = 'kuresel_etki_zinciri');

-- Only drop tables if this is explicitly requested (for development)
-- Uncomment the following lines if you want to reset all data
/*
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS user_tokens;
DROP TABLE IF EXISTS validation_records;
DROP TABLE IF EXISTS impact_scores;
DROP TABLE IF EXISTS supply_chain_steps;
DROP TABLE IF EXISTS product_batches;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS validators;
DROP TABLE IF EXISTS companies;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS blockchain_records;
DROP TABLE IF EXISTS audit_logs;
SET FOREIGN_KEY_CHECKS = 1;
*/

-- ========================================
-- Core User Management
-- ========================================

-- Users table - Platform kullanıcıları
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    user_type ENUM('admin', 'company', 'validator', 'consumer') NOT NULL,
    status ENUM('active', 'inactive', 'suspended', 'pending') DEFAULT 'pending',
    email_verified BOOLEAN DEFAULT FALSE,
    profile_image VARCHAR(255),
    language CHAR(2) DEFAULT 'tr',
    timezone VARCHAR(50) DEFAULT 'Europe/Istanbul',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    
    INDEX idx_email (email),
    INDEX idx_user_type (user_type),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- User Tokens table - For remember me and password reset tokens
CREATE TABLE IF NOT EXISTS user_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    type ENUM('remember_me', 'password_reset', 'email_verification') NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_type (user_id, type),
    INDEX idx_expires (expires_at),
    UNIQUE KEY unique_user_type (user_id, type)
) ENGINE=InnoDB;

-- User Preferences table - User settings and preferences
CREATE TABLE IF NOT EXISTS user_preferences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    preference_key VARCHAR(100) NOT NULL,
    preference_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_key (user_id, preference_key),
    UNIQUE KEY unique_user_preference (user_id, preference_key)
) ENGINE=InnoDB;

-- Two-Factor Authentication table
CREATE TABLE IF NOT EXISTS user_2fa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    secret VARCHAR(255) NOT NULL,
    backup_codes JSON,
    is_enabled BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_2fa (user_id)
) ENGINE=InnoDB;

-- User Devices table - Track user sessions and devices
CREATE TABLE IF NOT EXISTS user_devices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    device_name VARCHAR(255),
    device_type VARCHAR(50),
    device_os VARCHAR(50),
    browser VARCHAR(100),
    ip_address VARCHAR(45),
    session_id VARCHAR(128),
    is_current BOOLEAN DEFAULT FALSE,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_session (user_id, session_id),
    INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB;

-- ========================================
-- Company and Organization Management
-- ========================================

-- Companies table - Tedarik zinciri katılımcıları
CREATE TABLE IF NOT EXISTS companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) UNIQUE NOT NULL,
    user_id INT NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    legal_name VARCHAR(255),
    tax_number VARCHAR(50),
    industry_sector VARCHAR(100),
    company_type ENUM('supplier', 'manufacturer', 'distributor', 'retailer', 'farmer', 'fisher', 'logistics') NOT NULL,
    registration_number VARCHAR(100),
    website VARCHAR(255),
    description TEXT,
    
    -- Address Information
    address_line1 VARCHAR(255),
    address_line2 VARCHAR(255),
    city VARCHAR(100),
    state VARCHAR(100),
    postal_code VARCHAR(20),
    country VARCHAR(100),
    
    -- Contact Information
    contact_person VARCHAR(255),
    contact_email VARCHAR(255),
    contact_phone VARCHAR(20),
    
    -- Certification and Compliance
    certifications JSON,
    compliance_documents JSON,
    
    -- Reputation and Scoring
    transparency_score DECIMAL(5,2) DEFAULT 0.00,
    reputation_score DECIMAL(5,2) DEFAULT 0.00,
    total_products INT DEFAULT 0,
    verified_data_percentage DECIMAL(5,2) DEFAULT 0.00,
    
    -- Blockchain Integration
    blockchain_address VARCHAR(42),
    
    status ENUM('active', 'inactive', 'suspended', 'under_review') DEFAULT 'under_review',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_company_type (company_type),
    INDEX idx_industry_sector (industry_sector),
    INDEX idx_transparency_score (transparency_score),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ========================================
-- Validator System
-- ========================================

-- Validators table - Bağımsız denetçiler
CREATE TABLE IF NOT EXISTS validators (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) UNIQUE NOT NULL,
    user_id INT NOT NULL,
    validator_name VARCHAR(255) NOT NULL,
    organization_type ENUM('ngo', 'certification_body', 'audit_firm', 'government', 'independent') NOT NULL,
    specialization JSON,
    credentials JSON,
    
    -- Location and Coverage
    service_regions JSON,
    
    -- Reputation and Performance
    reputation_score DECIMAL(5,2) DEFAULT 0.00,
    total_validations INT DEFAULT 0,
    successful_validations INT DEFAULT 0,
    average_response_time INT DEFAULT 0,
    
    -- Token Economics
    token_balance DECIMAL(18,8) DEFAULT 0.00000000,
    stake_amount DECIMAL(18,8) DEFAULT 0.00000000,
    
    -- Blockchain Integration
    blockchain_address VARCHAR(42),
    
    status ENUM('active', 'inactive', 'suspended', 'under_review') DEFAULT 'under_review',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_organization_type (organization_type),
    INDEX idx_reputation_score (reputation_score),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ========================================
-- Product Management
-- ========================================

-- Products table - Ürün bilgileri
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) UNIQUE NOT NULL,
    company_id INT NOT NULL,
    
    -- Basic Product Information
    product_name VARCHAR(255) NOT NULL,
    product_code VARCHAR(100) UNIQUE,
    barcode VARCHAR(50),
    category VARCHAR(100),
    subcategory VARCHAR(100),
    brand VARCHAR(100),
    description TEXT,
    
    -- Product Specifications
    weight DECIMAL(10,3),
    volume DECIMAL(10,3),
    dimensions JSON,
    packaging_type VARCHAR(100),
    shelf_life INT,
    
    -- Origin Information
    origin_country VARCHAR(100),
    origin_region VARCHAR(100),
    harvest_season VARCHAR(50),
    
    -- Images and Documentation
    product_images JSON,
    documentation JSON,
    
    -- QR Code
    qr_code_path VARCHAR(255),
    qr_code_data TEXT,
    
    -- Status and Tracking
    status ENUM('active', 'inactive', 'discontinued') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    INDEX idx_product_code (product_code),
    INDEX idx_barcode (barcode),
    INDEX idx_category (category),
    INDEX idx_company_id (company_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Product Batches table - Ürün partileri
CREATE TABLE IF NOT EXISTS product_batches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) UNIQUE NOT NULL,
    product_id INT NOT NULL,
    
    -- Batch Information
    batch_number VARCHAR(100) NOT NULL,
    production_date DATE,
    expiry_date DATE,
    quantity INT NOT NULL,
    unit VARCHAR(20) DEFAULT 'pieces',
    
    -- Production Details
    production_facility VARCHAR(255),
    production_line VARCHAR(100),
    quality_grade CHAR(1),
    
    -- Blockchain Integration
    blockchain_hash VARCHAR(66),
    block_number BIGINT,
    
    status ENUM('in_production', 'in_transit', 'delivered', 'recalled') DEFAULT 'in_production',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_batch_number (batch_number),
    INDEX idx_production_date (production_date),
    INDEX idx_status (status),
    UNIQUE KEY unique_batch_product (product_id, batch_number)
) ENGINE=InnoDB;

-- ========================================
-- Supply Chain Tracking
-- ========================================

-- Supply Chain Steps table - Tedarik zinciri adımları
CREATE TABLE IF NOT EXISTS supply_chain_steps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) UNIQUE NOT NULL,
    product_batch_id INT NOT NULL,
    company_id INT NOT NULL,
    
    -- Step Information
    step_type ENUM('raw_material', 'processing', 'manufacturing', 'packaging', 'logistics', 'retail') NOT NULL,
    step_name VARCHAR(255) NOT NULL,
    step_description TEXT,
    step_order INT NOT NULL,
    
    -- Location and Timing
    location_coordinates JSON,
    address VARCHAR(500),
    start_date TIMESTAMP,
    end_date TIMESTAMP,
    duration_hours INT,
    
    -- Environmental Data
    carbon_emissions DECIMAL(12,6),
    water_usage DECIMAL(12,3),
    energy_consumption DECIMAL(12,6),
    waste_generated DECIMAL(12,3),
    renewable_energy_percentage DECIMAL(5,2),
    
    -- Social Data
    worker_count INT,
    average_wage DECIMAL(10,2),
    working_hours_per_day DECIMAL(4,2),
    safety_incidents INT,
    worker_satisfaction_score DECIMAL(3,2),
    
    -- Transportation Data
    transport_mode VARCHAR(50),
    distance_km DECIMAL(10,2),
    fuel_type VARCHAR(50),
    fuel_consumption DECIMAL(10,4),
    
    -- Certifications and Documentation
    certificates JSON,
    documents JSON,
    
    -- Validation Status
    validation_status ENUM('pending', 'validated', 'rejected', 'under_review') DEFAULT 'pending',
    validation_score DECIMAL(5,2),
    
    -- Blockchain Integration
    blockchain_hash VARCHAR(66),
    block_number BIGINT,
    ipfs_hash VARCHAR(64),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (product_batch_id) REFERENCES product_batches(id) ON DELETE CASCADE,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    INDEX idx_step_type (step_type),
    INDEX idx_validation_status (validation_status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- ========================================
-- Impact Scoring System
-- ========================================

-- Impact Scores table - Etki skorları
CREATE TABLE IF NOT EXISTS impact_scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) UNIQUE NOT NULL,
    product_batch_id INT NOT NULL,
    
    -- Overall Scores
    overall_score DECIMAL(5,2) NOT NULL,
    overall_grade CHAR(1) NOT NULL,
    
    -- Environmental Scores
    environmental_score DECIMAL(5,2) NOT NULL,
    carbon_footprint_score DECIMAL(5,2),
    water_footprint_score DECIMAL(5,2),
    biodiversity_impact_score DECIMAL(5,2),
    waste_score DECIMAL(5,2),
    
    -- Social Scores
    social_score DECIMAL(5,2) NOT NULL,
    fair_wages_score DECIMAL(5,2),
    working_conditions_score DECIMAL(5,2),
    community_impact_score DECIMAL(5,2),
    labor_rights_score DECIMAL(5,2),
    
    -- Transparency Scores
    transparency_score DECIMAL(5,2) NOT NULL,
    data_completeness_score DECIMAL(5,2),
    third_party_validation_score DECIMAL(5,2),
    update_frequency_score DECIMAL(5,2),
    source_credibility_score DECIMAL(5,2),
    
    -- Calculated Metrics
    total_carbon_footprint DECIMAL(12,6),
    total_water_footprint DECIMAL(12,3),
    total_energy_consumption DECIMAL(12,6),
    total_waste_generated DECIMAL(12,3),
    
    -- Comparative Metrics
    equivalent_car_km DECIMAL(10,2),
    equivalent_shower_minutes DECIMAL(10,2),
    equivalent_home_energy_days DECIMAL(8,2),
    
    -- Calculation Metadata
    calculation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    calculation_method VARCHAR(100),
    data_completeness_percentage DECIMAL(5,2),
    confidence_level DECIMAL(5,2),
    
    -- Blockchain Integration
    blockchain_hash VARCHAR(66),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (product_batch_id) REFERENCES product_batches(id) ON DELETE CASCADE,
    INDEX idx_overall_grade (overall_grade),
    INDEX idx_overall_score (overall_score),
    INDEX idx_environmental_score (environmental_score),
    INDEX idx_social_score (social_score),
    INDEX idx_transparency_score (transparency_score)
) ENGINE=InnoDB;

-- ========================================
-- Validation System
-- ========================================

-- Validation Records table - Doğrulama kayıtları
CREATE TABLE IF NOT EXISTS validation_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) UNIQUE NOT NULL,
    supply_chain_step_id INT NOT NULL,
    validator_id INT NOT NULL,
    
    -- Validation Details
    validation_type ENUM('document', 'field_visit', 'third_party_audit', 'blockchain_verification') NOT NULL,
    validation_method VARCHAR(100),
    validation_criteria JSON,
    
    -- Results
    validation_result ENUM('approved', 'rejected', 'needs_clarification') NOT NULL,
    confidence_score DECIMAL(5,2),
    findings TEXT,
    recommendations TEXT,
    
    -- Evidence
    evidence_documents JSON,
    evidence_photos JSON,
    evidence_blockchain_refs JSON,
    
    -- Timing
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    response_time_hours INT,
    
    -- Token Economics
    validation_fee DECIMAL(18,8),
    reward_amount DECIMAL(18,8),
    stake_amount DECIMAL(18,8),
    
    -- Blockchain Integration
    blockchain_hash VARCHAR(66),
    
    status ENUM('pending', 'in_progress', 'completed', 'disputed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (supply_chain_step_id) REFERENCES supply_chain_steps(id) ON DELETE CASCADE,
    FOREIGN KEY (validator_id) REFERENCES validators(id) ON DELETE CASCADE,
    INDEX idx_validation_type (validation_type),
    INDEX idx_validation_result (validation_result),
    INDEX idx_status (status),
    INDEX idx_requested_at (requested_at)
) ENGINE=InnoDB;

-- ========================================
-- Blockchain Integration
-- ========================================

-- Blockchain Records table - Blokzincir kayıtları
CREATE TABLE IF NOT EXISTS blockchain_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) UNIQUE NOT NULL,
    
    -- Transaction Details
    transaction_hash VARCHAR(66) UNIQUE NOT NULL,
    block_number BIGINT NOT NULL,
    block_hash VARCHAR(66),
    transaction_index INT,
    
    -- Record Information
    record_type ENUM('supply_chain_step', 'validation', 'impact_score', 'product_registration') NOT NULL,
    record_id INT NOT NULL,
    contract_address VARCHAR(42),
    
    -- Data Integrity
    data_hash VARCHAR(66) NOT NULL,
    ipfs_hash VARCHAR(64),
    
    -- Gas and Fees
    gas_used BIGINT,
    gas_price BIGINT,
    transaction_fee DECIMAL(18,8),
    
    -- Status
    confirmation_status ENUM('pending', 'confirmed', 'failed') DEFAULT 'pending',
    confirmation_count INT DEFAULT 0,
    
    -- Metadata
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    confirmed_at TIMESTAMP NULL,
    
    INDEX idx_transaction_hash (transaction_hash),
    INDEX idx_record_type (record_type),
    INDEX idx_record_id (record_id),
    INDEX idx_confirmation_status (confirmation_status)
) ENGINE=InnoDB;

-- ========================================
-- Audit and Logging
-- ========================================

-- Audit Logs table - Denetim kayıtları
CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) UNIQUE NOT NULL,
    
    -- Actor Information
    user_id INT,
    user_type VARCHAR(50),
    ip_address VARCHAR(45),
    user_agent TEXT,
    
    -- Action Details
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(100),
    record_id INT,
    
    -- Changes
    old_values JSON,
    new_values JSON,
    
    -- Context
    request_id VARCHAR(36),
    session_id VARCHAR(128),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_table_name (table_name),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- ========================================
-- Performance Indexes
-- ========================================

-- Create indexes only if they don't exist
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
     WHERE table_schema = 'kuresel_etki_zinciri' 
     AND table_name = 'companies' 
     AND index_name = 'idx_company_transparency') = 0,
    'CREATE INDEX idx_company_transparency ON companies(transparency_score DESC, status)',
    'SELECT "Index idx_company_transparency already exists"'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
     WHERE table_schema = 'kuresel_etki_zinciri' 
     AND table_name = 'products' 
     AND index_name = 'idx_product_company_category') = 0,
    'CREATE INDEX idx_product_company_category ON products(company_id, category, status)',
    'SELECT "Index idx_product_company_category already exists"'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
     WHERE table_schema = 'kuresel_etki_zinciri' 
     AND table_name = 'supply_chain_steps' 
     AND index_name = 'idx_supply_chain_batch_order') = 0,
    'CREATE INDEX idx_supply_chain_batch_order ON supply_chain_steps(product_batch_id, step_order)',
    'SELECT "Index idx_supply_chain_batch_order already exists"'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
     WHERE table_schema = 'kuresel_etki_zinciri' 
     AND table_name = 'validation_records' 
     AND index_name = 'idx_validation_validator_status') = 0,
    'CREATE INDEX idx_validation_validator_status ON validation_records(validator_id, status, requested_at)',
    'SELECT "Index idx_validation_validator_status already exists"'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
     WHERE table_schema = 'kuresel_etki_zinciri' 
     AND table_name = 'impact_scores' 
     AND index_name = 'idx_impact_score_grade_date') = 0,
    'CREATE INDEX idx_impact_score_grade_date ON impact_scores(overall_grade, calculation_date DESC)',
    'SELECT "Index idx_impact_score_grade_date already exists"'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ========================================
-- Initial Data
-- ========================================

-- Insert default admin user only if it doesn't exist
INSERT IGNORE INTO users (uuid, email, password_hash, first_name, last_name, user_type, status, email_verified) 
VALUES (
    UUID(), 
    'admin@kuresaletzinciri.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'System', 
    'Administrator', 
    'admin', 
    'active', 
    TRUE
);

-- Insert sample test company only if it doesn't exist
INSERT IGNORE INTO users (uuid, email, password_hash, first_name, last_name, user_type, status, email_verified) 
VALUES (
    UUID(), 
    'test@company.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'Test', 
    'Company', 
    'company', 
    'active', 
    TRUE
);

-- Insert sample test validator only if it doesn't exist
INSERT IGNORE INTO users (uuid, email, password_hash, first_name, last_name, user_type, status, email_verified) 
VALUES (
    UUID(), 
    'test@validator.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    'Test', 
    'Validator', 
    'validator', 
    'active', 
    TRUE
);

COMMIT;

SELECT 'Database schema installation completed successfully!' as Status;