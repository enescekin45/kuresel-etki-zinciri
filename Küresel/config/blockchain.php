<?php
/**
 * Blockchain Configuration
 * 
 * Configuration settings for blockchain interaction and smart contracts
 */

return [
    'blockchain' => [
        // Ethereum/Web3 Configuration
        'rpc_url' => $_ENV['BLOCKCHAIN_RPC_URL'] ?? 'http://localhost:8545',
        'network_id' => $_ENV['BLOCKCHAIN_NETWORK_ID'] ?? '1337', // Local development
        'gas_limit' => $_ENV['BLOCKCHAIN_GAS_LIMIT'] ?? '3000000',
        'gas_price' => $_ENV['BLOCKCHAIN_GAS_PRICE'] ?? '20000000000', // 20 gwei
        
        // Smart Contract Addresses
        'contracts' => [
            'supply_chain_tracker' => $_ENV['CONTRACT_SUPPLY_CHAIN'] ?? '0x0000000000000000000000000000000000000000',
            'product_nft' => $_ENV['CONTRACT_PRODUCT_NFT'] ?? '0x0000000000000000000000000000000000000000',
            'validation_token' => $_ENV['CONTRACT_VALIDATION_TOKEN'] ?? '0x0000000000000000000000000000000000000000'
        ],
        
        // Platform Wallet Configuration
        'platform_wallet' => [
            'address' => $_ENV['PLATFORM_WALLET_ADDRESS'] ?? '',
            'private_key' => $_ENV['PLATFORM_WALLET_PRIVATE_KEY'] ?? ''
        ],
        
        // Oracle Network Configuration
        'oracle' => [
            'validation_fee' => '0.01', // ETH
            'reputation_threshold' => 0.8,
            'min_validators' => 3,
            'validation_timeout' => 86400 // 24 hours in seconds
        ],
        
        // Token Economics
        'tokens' => [
            'validation_reward' => '10', // Validation tokens per successful validation
            'data_entry_cost' => '1', // Tokens required for data entry
            'reputation_penalty' => '50' // Tokens lost for false validation
        ]
    ],
    
    // IPFS Configuration for storing large files
    'ipfs' => [
        'gateway_url' => $_ENV['IPFS_GATEWAY'] ?? 'http://localhost:8080',
        'api_url' => $_ENV['IPFS_API'] ?? 'http://localhost:5001'
    ]
];
?>