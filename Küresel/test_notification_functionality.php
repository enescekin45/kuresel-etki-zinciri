<?php
require_once 'config/bootstrap.php';

// Test the notification functionality
try {
    $auth = Auth::getInstance();
    
    if (!$auth->isLoggedIn()) {
        // Redirect to login if not logged in
        header('Location: /Küresel/?page=login');
        exit;
    }
    
    $user = $auth->getCurrentUser();
    
    // Test sending a notification
    $notificationService = new NotificationService();
    
    // Send a test notification
    $result = $notificationService->sendNotificationToUser(
        $user,
        'both', // Send both email and SMS
        'Test Bildirimi',
        'Bu, bildirim sisteminin düzgün çalıştığını test etmek için gönderilen bir test bildirimidir.'
    );
    
    echo "<h1>Test Sonuçları</h1>";
    echo "<p>Bildirim gönderimi: " . ($result ? "Başarılı" : "Başarısız") . "</p>";
    
    // Show user's notification preferences
    $preferences = $user->getPreferences();
    echo "<h2>Kullanıcı Bildirim Tercihleri:</h2>";
    echo "<pre>" . print_r($preferences, true) . "</pre>";
    
    // Show user's contact information
    echo "<h2>Kullanıcı İletişim Bilgileri:</h2>";
    echo "<p>E-posta: " . $user->getEmail() . "</p>";
    echo "<p>Telefon: " . ($user->getPhone() ?: "Tanımlı değil") . "</p>";
    
} catch (Exception $e) {
    echo "<h1>Hata</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>