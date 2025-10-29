<?php
require_once '../../../config/app.php';
require_once '../../../config/database.php';

// Check if user is logged in
$auth = Auth::getInstance();
if (!$auth->isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Yetkisiz erişim']);
    exit;
}

header('Content-Type: application/json');

try {
    $currentUser = $auth->getCurrentUser();
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method === 'POST') {
        // Handle file upload
        if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Geçersiz dosya yükleme');
        }
        
        $file = $_FILES['profile_image'];
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Sadece JPEG, PNG ve GIF dosyaları yüklenebilir');
        }
        
        // Validate file size (max 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            throw new Exception('Dosya boyutu maksimum 2MB olabilir');
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'profile_' . $currentUser->getId() . '_' . time() . '.' . $extension;
        $uploadDir = '../../../uploads/profiles/';
        
        // Create upload directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $uploadPath = $uploadDir . $filename;
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            throw new Exception('Dosya yüklenirken hata oluştu');
        }
        
        // Save to database
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
        $result = $stmt->execute([$filename, $currentUser->getId()]);
        
        if (!$result) {
            // Delete uploaded file if database update fails
            unlink($uploadPath);
            throw new Exception('Profil resmi veritabanına kaydedilemedi');
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Profil fotoğrafı başarıyla güncellendi',
            'data' => [
                'profile_image' => $filename
            ]
        ]);
    } elseif ($method === 'DELETE') {
        // Handle file removal
        // Get current profile image
        $currentProfileImage = $currentUser->getProfileImage();
        
        if ($currentProfileImage) {
            // Delete file from filesystem
            $uploadPath = '../../../uploads/profiles/' . $currentProfileImage;
            if (file_exists($uploadPath)) {
                unlink($uploadPath);
            }
            
            // Remove from database
            $db = Database::getInstance();
            $stmt = $db->prepare("UPDATE users SET profile_image = NULL WHERE id = ?");
            $result = $stmt->execute([$currentUser->getId()]);
            
            if (!$result) {
                throw new Exception('Profil resmi veritabanından kaldırılamadı');
            }
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Profil fotoğrafı başarıyla kaldırıldı'
        ]);
    } else {
        throw new Exception('Geçersiz istek metodu');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}