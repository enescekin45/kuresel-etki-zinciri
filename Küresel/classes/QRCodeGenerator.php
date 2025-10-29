<?php
/**
 * QR Code Generator Class
 * 
 * Handles QR code generation for products and other entities
 */

require_once ROOT_DIR . '/vendor/autoload.php';

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QRCodeGenerator {
    private $qrCodePath;
    private $baseUrl;
    
    public function __construct() {
        $this->qrCodePath = QR_CODES_DIR;
        $this->baseUrl = $_ENV['APP_URL'] ?? 'http://localhost/Küresel';
        
        // Create QR codes directory if it doesn't exist
        if (!is_dir($this->qrCodePath)) {
            mkdir($this->qrCodePath, 0755, true);
        }
    }
    
    /**
     * Generate QR code for a product
     */
    public function generateProductQR($productId, $productUuid, $productName) {
        $qrData = [
            'type' => 'product',
            'product_id' => $productId,
            'product_uuid' => $productUuid,
            'url' => $this->baseUrl . '/product?uuid=' . $productUuid,
            'generated_at' => date('c'),
            'version' => '1.0'
        ];
        
        $filename = 'product-' . $productId . '.png';
        $filePath = $this->qrCodePath . '/' . $filename;
        
        $this->generateQRCode(json_encode($qrData), $filePath, [
            'title' => $productName,
            'subtitle' => 'Ürün Bilgilerini Görüntüle'
        ]);
        
        return [
            'file_path' => '/qr-codes/' . $filename,
            'full_path' => $filePath,
            'data' => json_encode($qrData),
            'url' => $qrData['url']
        ];
    }
    
    /**
     * Generate QR code for a batch
     */
    public function generateBatchQR($batchId, $batchNumber, $productUuid) {
        $qrData = [
            'type' => 'batch',
            'batch_id' => $batchId,
            'batch_number' => $batchNumber,
            'product_uuid' => $productUuid,
            'url' => $this->baseUrl . '/product?uuid=' . $productUuid . '&batch=' . $batchId,
            'generated_at' => date('c'),
            'version' => '1.0'
        ];
        
        $filename = 'batch-' . $batchId . '.png';
        $filePath = $this->qrCodePath . '/' . $filename;
        
        $this->generateQRCode(json_encode($qrData), $filePath, [
            'title' => 'Parti: ' . $batchNumber,
            'subtitle' => 'Parti Bilgilerini Görüntüle'
        ]);
        
        return [
            'file_path' => '/qr-codes/' . $filename,
            'full_path' => $filePath,
            'data' => json_encode($qrData),
            'url' => $qrData['url']
        ];
    }
    
    /**
     * Generate QR code for a company
     */
    public function generateCompanyQR($companyId, $companyUuid, $companyName) {
        $qrData = [
            'type' => 'company',
            'company_id' => $companyId,
            'company_uuid' => $companyUuid,
            'url' => $this->baseUrl . '/company?uuid=' . $companyUuid,
            'generated_at' => date('c'),
            'version' => '1.0'
        ];
        
        $filename = 'company-' . $companyId . '.png';
        $filePath = $this->qrCodePath . '/' . $filename;
        
        $this->generateQRCode(json_encode($qrData), $filePath, [
            'title' => $companyName,
            'subtitle' => 'Şirket Bilgilerini Görüntüle'
        ]);
        
        return [
            'file_path' => '/qr-codes/' . $filename,
            'full_path' => $filePath,
            'data' => json_encode($qrData),
            'url' => $qrData['url']
        ];
    }
    
    /**
     * Generate basic QR code
     */
    private function generateQRCode($data, $filePath, $options = []) {
        $qrOptions = new QROptions([
            'version'      => 5,
            'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'     => QRCode::ECC_L,
            'scale'        => 10,
            'imageBase64'  => false,
            'moduleValues' => [
                // finder
                1536 => [0, 63, 255], // dark (true)
                6    => [255, 255, 255], // light (false)
            ],
        ]);
        
        $qr = new QRCode($qrOptions);
        
        try {
            $qrImage = $qr->render($data);
            
            // If we have custom options, we might want to add text or logos
            if (!empty($options)) {
                $qrImage = $this->addCustomElements($qrImage, $options);
            }
            
            file_put_contents($filePath, $qrImage);
            
            return true;
            
        } catch (Exception $e) {
            error_log("QR Code generation error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Add custom elements to QR code (placeholder for future enhancement)
     */
    private function addCustomElements($qrImage, $options) {
        // This could be enhanced to add logos, text, etc.
        // For now, just return the original image
        return $qrImage;
    }
    
    /**
     * Generate QR code for URL
     */
    public function generateUrlQR($url, $filename, $title = null) {
        $filePath = $this->qrCodePath . '/' . $filename;
        
        $this->generateQRCode($url, $filePath, [
            'title' => $title ?: 'QR Code'
        ]);
        
        return [
            'file_path' => '/qr-codes/' . $filename,
            'full_path' => $filePath,
            'url' => $url
        ];
    }
    
    /**
     * Get QR code file info
     */
    public function getQRCodeInfo($filename) {
        $filePath = $this->qrCodePath . '/' . $filename;
        
        if (!file_exists($filePath)) {
            return null;
        }
        
        return [
            'file_path' => '/qr-codes/' . $filename,
            'full_path' => $filePath,
            'size' => filesize($filePath),
            'created' => filemtime($filePath)
        ];
    }
    
    /**
     * Delete QR code file
     */
    public function deleteQRCode($filename) {
        $filePath = $this->qrCodePath . '/' . $filename;
        
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        
        return true;
    }
    
    /**
     * Clean up old QR codes
     */
    public function cleanupOldQRCodes($maxAge = 2592000) { // 30 days default
        $files = glob($this->qrCodePath . '/*.png');
        $deleted = 0;
        
        foreach ($files as $file) {
            if (filemtime($file) < (time() - $maxAge)) {
                if (unlink($file)) {
                    $deleted++;
                }
            }
        }
        
        return $deleted;
    }
    
    /**
     * Validate QR code data
     */
    public function validateQRData($qrData) {
        $data = json_decode($qrData, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['valid' => false, 'error' => 'Invalid JSON format'];
        }
        
        if (!isset($data['type'])) {
            return ['valid' => false, 'error' => 'Missing type field'];
        }
        
        switch ($data['type']) {
            case 'product':
                if (!isset($data['product_uuid'])) {
                    return ['valid' => false, 'error' => 'Missing product UUID'];
                }
                break;
                
            case 'batch':
                if (!isset($data['batch_id']) || !isset($data['product_uuid'])) {
                    return ['valid' => false, 'error' => 'Missing batch ID or product UUID'];
                }
                break;
                
            case 'company':
                if (!isset($data['company_uuid'])) {
                    return ['valid' => false, 'error' => 'Missing company UUID'];
                }
                break;
                
            default:
                return ['valid' => false, 'error' => 'Unknown QR code type'];
        }
        
        return ['valid' => true, 'data' => $data];
    }
    
    /**
     * Get QR code statistics
     */
    public function getQRCodeStats() {
        $files = glob($this->qrCodePath . '/*.png');
        $totalSize = 0;
        
        foreach ($files as $file) {
            $totalSize += filesize($file);
        }
        
        return [
            'total_files' => count($files),
            'total_size' => $totalSize,
            'total_size_mb' => round($totalSize / 1024 / 1024, 2),
            'directory' => $this->qrCodePath
        ];
    }
}
?>