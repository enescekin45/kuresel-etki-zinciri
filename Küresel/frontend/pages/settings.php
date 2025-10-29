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

<link rel="stylesheet" href="assets/css/style.css">

<main class="page-content">
    <div class="container">
        <div class="page-header">
            <h1>Hesap Ayarları</h1>
            <p>Profil bilgilerinizi yönetin</p>
        </div>
        
        <div class="settings-container">
            <div class="settings-card">
                <form id="settings-form" class="settings-form">
                    <div class="form-group">
                        <label for="first_name">Ad</label>
                        <input type="text" id="first_name" name="first_name" 
                               class="form-control" value="<?= htmlspecialchars($currentUser->getFirstName()) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name">Soyad</label>
                        <input type="text" id="last_name" name="last_name" 
                               class="form-control" value="<?= htmlspecialchars($currentUser->getLastName()) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">E-posta</label>
                        <input type="email" id="email" name="email" 
                               class="form-control" value="<?= htmlspecialchars($currentUser->getEmail()) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Telefon (İsteğe Bağlı)</label>
                        <input type="tel" id="phone" name="phone" 
                               class="form-control" value="<?= htmlspecialchars($currentUser->getPhone() ?? '') ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-large settings-submit">
                        💾 Ayarları Kaydet
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const settingsForm = document.getElementById('settings-form');
    
    // Form submission
    settingsForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        const submitBtn = this.querySelector('.settings-submit');
        const originalText = submitBtn.textContent;
        
        try {
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Kaydediliyor...';
            
            const response = await fetch('/Küresel/api/v1/auth/profile', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showNotification('✅ Ayarlarınız başarıyla güncellendi!', 'success');
            } else {
                showNotification('❌ ' + (result.message || 'Ayarlar güncellenemedi'), 'error');
            }
            
        } catch (error) {
            console.error('Settings update error:', error);
            showNotification('❌ Ayarlar kaydedilirken hata oluştu: ' + error.message, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
});

function showNotification(message, type) {
    // Remove any existing notifications
    const existing = document.querySelector('.notification');
    if (existing) {
        existing.remove();
    }
    
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}
</script>

<?php include FRONTEND_DIR . '/components/footer.php'; ?>