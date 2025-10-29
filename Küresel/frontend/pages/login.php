<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap - Küresel Etki Zinciri</title>
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
    
    <main class="login-page">
        <div class="container">
            <div class="auth-container">
                <div class="auth-card">
                    <div class="auth-header">
                        <h1>Giriş Yap</h1>
                        <p>Hesabınıza giriş yaparak platformun tüm özelliklerinden yararlanın</p>
                    </div>
                    
                    <form id="login-form" class="auth-form" data-api="/auth/login" data-redirect="/Küresel/?page=login">
                        <div class="form-group">
                            <label for="email">E-posta Adresi</label>
                            <input type="email" id="email" name="email" class="form-control" required 
                                   placeholder="ornek@email.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Şifre</label>
                            <div class="password-input">
                                <input type="password" id="password" name="password" class="form-control" required 
                                       placeholder="Şifrenizi girin">
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    👁️
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-options">
                            <label class="checkbox-label">
                                <input type="checkbox" name="remember_me" value="1">
                                <span class="checkmark"></span>
                                Beni hatırla
                            </label>
                            
                            <a href="/Küresel/?page=forgot-password" class="forgot-link">Şifremi unuttum</a>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-large auth-submit">
                            🔐 Giriş Yap
                        </button>
                        
                        <input type="hidden" name="csrf_token" value="<?= $auth->generateCSRFToken() ?>">
                    </form>
                    
                    <div class="auth-divider">
                        <span>veya</span>
                    </div>
                    
                    <div class="demo-accounts">
                        <h3>Demo Hesapları</h3>
                        <div class="demo-buttons">
                            <button class="btn btn-outline demo-login" data-email="admin@kuresaletzinciri.com" data-password="password">
                                👨‍💼 Admin Demo
                            </button>
                            <button class="btn btn-outline demo-login" data-email="test@company.com" data-password="password">
                                🏢 Şirket Demo
                            </button>
                            <button class="btn btn-outline demo-login" data-email="test@validator.com" data-password="password">
                                ✅ Validator Demo
                            </button>
                        </div>
                    </div>
                    
                    <div class="auth-footer">
                        <p>Hesabınız yok mu? <a href="/Küresel/?page=register">Kayıt olun</a></p>
                    </div>
                </div>
                
                <!-- Info Panel -->
                <div class="auth-info">
                    <h2>Platforma Hoş Geldiniz</h2>
                    <div class="info-features">
                        <div class="info-feature">
                            <div class="feature-icon">🔍</div>
                            <h3>Şeffaflık</h3>
                            <p>Tedarik zincirinizin her adımını şeffaf şekilde takip edin</p>
                        </div>
                        
                        <div class="info-feature">
                            <div class="feature-icon">🛡️</div>
                            <h3>Güvenlik</h3>
                            <p>Blockchain teknolojisi ile verileriniz güvende</p>
                        </div>
                        
                        <div class="info-feature">
                            <div class="feature-icon">📊</div>
                            <h3>Analitik</h3>
                            <p>Detaylı raporlar ve analitikler ile bilinçli kararlar verin</p>
                        </div>
                    </div>
                    
                    <div class="info-stats">
                        <div class="stat">
                            <div class="stat-number">1000+</div>
                            <div class="stat-label">Kayıtlı Şirket</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number">50K+</div>
                            <div class="stat-label">Takip Edilen Ürün</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number">100+</div>
                            <div class="stat-label">Validator</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include FRONTEND_DIR . '/components/footer.php'; ?>
    
    <script src="assets/js/main.js"></script>
    
    <script>
    // Password toggle
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const toggle = input.nextElementSibling;
        
        if (input.type === 'password') {
            input.type = 'text';
            toggle.textContent = '🙈';
        } else {
            input.type = 'password';
            toggle.textContent = '👁️';
        }
    }
    
    // Demo login functionality
    document.addEventListener('DOMContentLoaded', function() {
        const demoButtons = document.querySelectorAll('.demo-login');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const loginForm = document.getElementById('login-form');
        
        demoButtons.forEach(button => {
            button.addEventListener('click', function() {
                const email = this.getAttribute('data-email');
                const password = this.getAttribute('data-password');
                
                emailInput.value = email;
                passwordInput.value = password;
                
                // Auto-submit the form by calling the submit handler directly
                loginForm.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
            });
        });
        
        // Enhanced form submission handling
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Get form data correctly
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const rememberMe = document.querySelector('input[name="remember_me"]')?.checked || false;
            
            const submitBtn = this.querySelector('.auth-submit');
            const originalText = submitBtn.textContent;
            
            try {
                submitBtn.disabled = true;
                submitBtn.textContent = '🔄 Giriş yapılıyor...';
                
                const response = await fetch('/Küresel/api/v1/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password,
                        remember_me: rememberMe
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Show success message
                    showNotification('✅ Giriş başarılı! ' + 
                        (result.data.user.user_type === 'consumer' ? 'Tüketici' :
                         result.data.user.user_type === 'company' ? 'Şirket' :
                         result.data.user.user_type === 'validator' ? 'Doğrulayıcı' :
                         result.data.user.user_type === 'admin' ? 'Yönetici' : 'Kullanıcı') + 
                        ' paneline yönlendiriliyorsunuz...', 'success');
                    
                    // Redirect based on user type
                    setTimeout(() => {
                        const userType = result.data.user.user_type;
                        let redirectUrl = '/Küresel/'; // Default redirect
                        
                        switch(userType) {
                            case 'admin':
                                redirectUrl = '/Küresel/?page=admin';
                                break;
                            case 'company':
                                redirectUrl = '/Küresel/?page=company';
                                break;
                            case 'validator':
                                redirectUrl = '/Küresel/?page=validator';
                                break;
                            case 'consumer':
                                redirectUrl = '/Küresel/?page=consumer';
                                break;
                            default:
                                redirectUrl = '/Küresel/';
                        }
                        
                        // Debug: Show the redirect URL
                        console.log('Redirecting to:', redirectUrl);
                        
                        // Yönlendirme
                        window.location.href = redirectUrl;
                    }, 2000);
                } else {
                    showNotification('❌ ' + (result.message || 'Giriş başarısız'), 'error');
                }
                
            } catch (error) {
                console.error('Login error:', error);
                showNotification('❌ Giriş sırasında hata oluştu', 'error');
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
        }, 5000);
    }
    </script>
</body>
</html>
