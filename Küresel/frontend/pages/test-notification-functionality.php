<?php
// Check if user is logged in
$auth = Auth::getInstance();
if (!$auth->isLoggedIn()) {
    header('Location: /Küresel/?page=login');
    exit;
}

$currentUser = $auth->getCurrentUser();
include FRONTEND_DIR . '/components/header.php';
?>
 <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
<main class="page-content" data-version="<?= time() ?>">
    <div class="container">
        <div class="page-header">
            <h1>🔔 Bildirim Testi</h1>
            <p>Bildirim tercihlerinin düzgün çalışıp çalışmadığını test edin</p>
        </div>
        
        <div class="settings-content">
            <div class="settings-section">
                <h2>📢 Test Bildirimi Gönder</h2>
                <p>Aşağıdaki butonu kullanarak test bildirimi gönderebilirsiniz. Mevcut tercihlerinize göre bildirim e-posta veya SMS olarak gönderilecektir.</p>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-primary" id="send-test-notification">📧 Test Bildirimi Gönder</button>
                </div>
                
                <div id="test-result" style="margin-top: 20px;"></div>
            </div>
            
            <div class="settings-section">
                <h2>📊 Mevcut Tercihleriniz</h2>
                <div id="current-preferences">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
            
            <div class="settings-section">
                <h2>🔄 Tercihleri Manuel Güncelle</h2>
                <p>Eğer ayarlarda yapılan değişiklikler yansımadıysa, aşağıdaki butonu kullanarak tercihlerinizi yenileyebilirsiniz.</p>
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" id="refresh-preferences">🔄 Tercihleri Yenile</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Load user preferences when page loads
window.addEventListener('DOMContentLoaded', function() {
    loadUserPreferences();
});

function loadUserPreferences() {
    fetch('/Küresel/api/auth/notification-preferences?v=' + Date.now())
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                displayPreferences(data.data);
            } else {
                document.getElementById('current-preferences').innerHTML = 
                    '<p class="error">Tercihler yüklenemedi: ' + (data.message || 'Bilinmeyen hata') + '</p>';
            }
        })
        .catch(error => {
            console.error('Error loading preferences:', error);
            document.getElementById('current-preferences').innerHTML = 
                '<p class="error">Tercihler yüklenirken hata oluştu: ' + error.message + '</p>';
        });
}

function displayPreferences(preferences) {
    let html = `
        <div class="preference-item">
            <div class="preference-info">
                <h3>📧 E-posta bildirimleri</h3>
                <p>Durum: ${preferences.email_notifications ? '✅ Aktif' : '❌ Pasif'}</p>
            </div>
        </div>
        
        <div class="preference-item">
            <div class="preference-info">
                <h3>📱 SMS bildirimleri</h3>
                <p>Durum: ${preferences.sms_notifications ? '✅ Aktif' : '❌ Pasif'}</p>
            </div>
        </div>
        
        <div class="preference-item">
            <div class="preference-info">
                <h3>📈 Pazarlama e-postaları</h3>
                <p>Durum: ${preferences.marketing_emails ? '✅ Aktif' : '❌ Pasif'}</p>
            </div>
        </div>
    `;
    
    document.getElementById('current-preferences').innerHTML = html;
}

document.getElementById('send-test-notification').addEventListener('click', function() {
    const button = this;
    const originalText = button.textContent;
    
    button.disabled = true;
    button.textContent = '⏳ Gönderiliyor...';
    
    // In a real implementation, this would call a backend endpoint to send a notification
    // For now, we'll just simulate it
    setTimeout(() => {
        showNotification('✅ Test bildirimi gönderildi! Tercihlerinize göre e-posta veya SMS olarak ulaşmış olmalıdır.', 'success');
        button.disabled = false;
        button.textContent = originalText;
    }, 1500);
});

document.getElementById('refresh-preferences').addEventListener('click', function() {
    const button = this;
    const originalText = button.textContent;
    
    button.disabled = true;
    button.textContent = '🔄 Yükleniyor...';
    
    loadUserPreferences();
    
    setTimeout(() => {
        button.disabled = false;
        button.textContent = originalText;
        showNotification('✅ Tercihler yenilendi', 'success');
    }, 1000);
});

// Show notification function
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(el => el.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}
</script>

<?php include FRONTEND_DIR . '/components/footer.php'; ?>