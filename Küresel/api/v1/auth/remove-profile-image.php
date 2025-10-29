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
    
    // Only handle DELETE requests
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        throw new Exception('Geçersiz istek metodu');
    }
    
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
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}