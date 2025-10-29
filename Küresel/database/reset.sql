-- Global Impact Chain Database - Reset Script
-- Küresel Etki Zinciri Veritabanı - Sıfırlama Scripti
-- UYARI: Bu script tüm verileri siler!
-- WARNING: This script deletes all data!

USE kuresel_etki_zinciri;

-- Disable foreign key checks for clean deletion
SET FOREIGN_KEY_CHECKS = 0;

-- Drop all tables in correct order
DROP TABLE IF EXISTS user_tokens;
DROP TABLE IF EXISTS validation_records;
DROP TABLE IF EXISTS impact_scores;
DROP TABLE IF EXISTS supply_chain_steps;
DROP TABLE IF EXISTS product_batches;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS validators;
DROP TABLE IF EXISTS companies;
DROP TABLE IF EXISTS blockchain_records;
DROP TABLE IF EXISTS audit_logs;
DROP TABLE IF EXISTS users;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

SELECT 'All tables have been dropped. Run install_safe.sql to recreate.' as Status;