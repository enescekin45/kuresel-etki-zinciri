<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifremi Unuttum - Küresel Etki Zinciri</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="<?= Auth::getInstance()->generateCSRFToken() ?>">
</head>
<body>
    <?php 
    // Check if user is already logged in
    $auth = Auth::getInstance();
    if ($auth->isLoggedIn()) {
        header('Location: /Küresel/');
        exit;
    }
    
    include FRONTEND_DIR . '/components/header.php'; 
    ?>
    
    <main class="forgot-password-page">
        <div class="container">
            <div class="auth-container">
                <div class="auth-card">
                    <div class="auth-header">
                        <h1>🔑 Şifremi Unuttum</h1>
                        <p>E-posta adresinizi girin, size şifre sıfırlama bağlantısı gönderelim</p>
                    </div>
                    
                    <form id="forgot-password-form" class="auth-form">
                        <div class="form-group">
                            <label for="email">E-posta Adresi</label>
                            <input type="email" id="email" name="email" class="form-control" required 
                                   placeholder="ornek@email.com" autofocus>
                            <small class="form-text">Kayıtlı e-posta adresinizi girin</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-large auth-submit">
                            📧 Sıfırlama Bağlantısı Gönder
                        </button>
                        
                        <input type="hidden" name="csrf_token" value="<?= $auth->generateCSRFToken() ?>">
                    </form>
                    
                    <div class="auth-divider">
                        <span>veya</span>
                    </div>
                    
                    <div class="auth-footer">
                        <p>Şifrenizi hatırladınız mı? <a href="/Küresel/?page=login">Giriş yapın</a></p>
                        <p>Hesabınız yok mu? <a href="/Küresel/?page=register">Kayıt olun</a></p>
                    </div>
                </div>
                
                <!-- Info Panel -->
                <div class="auth-info">
                    <h2>Şifre Sıfırlama Süreci</h2>
                    <div class="info-steps">
                        <div class="info-step">
                            <div class="step-number">1</div>
                            <h3>E-posta Girin</h3>
                            <p>Kayıtlı e-posta adresinizi yazın</p>
                        </div>
                        
                        <div class="info-step">
                            <div class="step-number">2</div>
                            <h3>E-postanızı Kontrol Edin</h3>
                            <p>Şifre sıfırlama bağlantısını içeren e-posta alacaksınız</p>
                        </div>
                        
                        <div class="info-step">
                            <div class="step-number">3</div>
                            <h3>Yeni Şifre Oluşturun</h3>
                            <p>Bağlantıya tıklayarak yeni şifrenizi belirleyin</p>
                        </div>
                        
                        <div class="info-step">
                            <div class="step-number">4</div>
                            <h3>Giriş Yapın</h3>
                            <p>Yeni şifrenizle platformumuza giriş yapın</p>
                        </div>
                    </div>
                    
                    <div class="help-box">
                        <h3>🆘 Yardıma mı ihtiyacınız var?</h3>
                        <p>E-posta almadıysanız:</p>
                        <ul>
                            <li>Spam klasörünüzü kontrol edin</li>
                            <li>E-posta adresinizin doğru olduğundan emin olun</li>
                            <li>Birkaç dakika bekleyip tekrar deneyin</li>
                        </ul>
                        <p>Hala sorun yaşıyorsanız, <a href="/Küresel/?page=contact">destek ekibimizle iletişime geçin</a></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include FRONTEND_DIR . '/components/footer.php'; ?>
    
    <script src="assets/js/main.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const forgotPasswordForm = document.getElementById('forgot-password-form');
        
        forgotPasswordForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('.auth-submit');
            const originalText = submitBtn.textContent;
            
            try {
                submitBtn.disabled = true;
                submitBtn.textContent = '📤 Gönderiliyor...';
                
                const email = formData.get('email');
                const response = await fetch('/Küresel/api/v1/auth/forgot-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email: email })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('✅ Şifre sıfırlama bağlantısı e-posta adresinize gönderildi. Lütfen e-postanızı kontrol edin.', 'success');
                    
                    // Clear the form
                    this.reset();
                    
                    // Redirect to login page after 5 seconds
                    setTimeout(() => {
                        window.location.href = '/Küresel/?page=login';
                    }, 5000);
                } else {
                    showNotification('⚠️ ' + (result.message || 'E-posta gönderilirken bir hata oluştu'), 'warning');
                }
                
            } catch (error) {
                console.error('Forgot password error:', error);
                showNotification('❌ İşlem sırasında hata oluştu. Lütfen tekrar deneyin.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    });
    
    function showNotification(message, type) {
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
        }, 8000);
    }
    </script>
</body>
</html>
