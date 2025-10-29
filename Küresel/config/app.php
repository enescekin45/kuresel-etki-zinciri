<?php
/**
 * Application Configuration
 * 
 * General application settings and business logic configuration
 */

// Define environment constant
if (!defined('APP_ENV')) {
    define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');
}

return [
    'app' => [
        'name' => 'Küresel Etki Zinciri',
        'version' => '1.0.0',
        'url' => $_ENV['APP_URL'] ?? 'http://localhost/Küresel',
        'timezone' => 'Europe/Istanbul',
        'locale' => 'tr_TR',
        'debug' => $_ENV['APP_DEBUG'] ?? true,
        
        // Security
        'jwt_secret' => $_ENV['JWT_SECRET'] ?? 'your-super-secret-jwt-key-change-this',
        'encryption_key' => $_ENV['ENCRYPTION_KEY'] ?? 'your-32-character-encryption-key123',
        'session_lifetime' => 3600, // 1 hour
        
        // File Upload Settings
        'max_file_size' => 10485760, // 10MB in bytes
        'allowed_file_types' => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'],
        
        // QR Code Settings
        'qr_size' => 300,
        'qr_margin' => 10,
        'qr_format' => 'png'
    ],
    
    // Impact Calculation Configuration
    'impact' => [
        // Environmental Impact Weights
        'environmental_weights' => [
            'carbon_footprint' => 0.4,
            'water_footprint' => 0.3,
            'biodiversity_impact' => 0.2,
            'waste_generation' => 0.1
        ],
        
        // Social Impact Weights
        'social_weights' => [
            'fair_wages' => 0.3,
            'working_conditions' => 0.3,
            'community_impact' => 0.2,
            'labor_rights' => 0.2
        ],
        
        // Transparency Scoring
        'transparency_weights' => [
            'data_completeness' => 0.4,
            'third_party_validation' => 0.3,
            'update_frequency' => 0.2,
            'source_credibility' => 0.1
        ],
        
        // Grade Thresholds (A-F scale)
        'grade_thresholds' => [
            'A' => 90,
            'B' => 80,
            'C' => 70,
            'D' => 60,
            'F' => 0
        ]
    ],
    
    // Validation System Configuration
    'validation' => [
        'required_validators' => 3,
        'validation_timeout_hours' => 48,
        'consensus_threshold' => 0.67, // 67% agreement required
        'reputation_decay_rate' => 0.05, // 5% per month
        'min_reputation_score' => 0.5
    ],
    
    // API Rate Limiting
    'rate_limiting' => [
        'requests_per_minute' => 60,
        'requests_per_hour' => 1000,
        'burst_limit' => 10
    ],
    
    // Notification Settings
    'notifications' => [
        'email_enabled' => true,
        'sms_enabled' => false,
        'push_enabled' => true,
        'validation_alerts' => true,
        'impact_score_alerts' => true
    ]
];
?>