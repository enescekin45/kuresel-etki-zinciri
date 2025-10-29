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
            <h1>Şifre Değiştir</h1>
            <p>Hesap şifrenizi güncelleyin</p>
        </div>
        
        <div class="auth-container">
            <div class="auth-card">
                <form id="change-password-form" class="auth-form">
                    <div class="form-group">
                        <label for="current_password">Mevcut Şifre</label>
                        <input type="password" id="current_password" name="current_password" 
                               class="form-control" required placeholder="Mevcut şifrenizi girin">
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">Yeni Şifre</label>
                        <div class="password-input">
                            <input type="password" id="new_password" name="new_password" 
                                   class="form-control" required placeholder="Yeni şifrenizi girin" 
                                   minlength="8">
                            <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                👁️
                            </button>
                        </div>
                        <div class="password-strength" id="password-strength"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Yeni Şifre (Tekrar)</label>
                        <div class="password-input">
                            <input type="password" id="confirm_password" name="confirm_password" 
                                   class="form-control" required placeholder="Yeni şifrenizi tekrar girin">
                            <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                👁️
                            </button>
                        </div>
                    </div>
                    
                    <div class="password-requirements">
                        <h4>Şifre Gereksinimleri:</h4>
                        <ul>
                            <li id="req-length">En az 8 karakter</li>
                            <li id="req-letter">En az bir harf</li>
                            <li id="req-number">En az bir rakam</li>
                            <li id="req-match">Şifreler eşleşmeli</li>
                        </ul>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-large auth-submit">
                        🔐 Şifre Değiştir
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

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

// Password strength checker
function checkPasswordStrength(password) {
    let strength = 0;
    const requirements = {
        length: password.length >= 8,
        letter: /[a-zA-Z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[^a-zA-Z0-9]/.test(password)
    };
    
    Object.values(requirements).forEach(req => {
        if (req) strength++;
    });
    
    return { strength, requirements };
}

// Update password requirements UI
function updatePasswordRequirements(password, confirmPassword) {
    const { requirements } = checkPasswordStrength(password);
    
    document.getElementById('req-length').className = requirements.length ? 'valid' : '';
    document.getElementById('req-letter').className = requirements.letter ? 'valid' : '';
    document.getElementById('req-number').className = requirements.number ? 'valid' : '';
    document.getElementById('req-match').className = 
        (password && confirmPassword && password === confirmPassword) ? 'valid' : '';
}

document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const changePasswordForm = document.getElementById('change-password-form');
    
    // Password validation
    passwordInput.addEventListener('input', function() {
        updatePasswordRequirements(this.value, confirmPasswordInput.value);
    });
    
    confirmPasswordInput.addEventListener('input', function() {
        updatePasswordRequirements(passwordInput.value, this.value);
    });
    
    // Form submission
    changePasswordForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Validate passwords match
        if (passwordInput.value !== confirmPasswordInput.value) {
            showNotification('❌ Şifreler eşleşmiyor', 'error');
            return;
        }
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        const submitBtn = this.querySelector('.auth-submit');
        const originalText = submitBtn.textContent;
        
        try {
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Şifre değiştiriliyor...';
            
            const response = await fetch('/Küresel/api/v1/auth/change-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    current_password: currentPassword,
                    new_password: newPassword,
                    confirm_password: confirmPassword
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                showNotification('✅ Şifreniz başarıyla değiştirildi!', 'success');
                // Reset form
                this.reset();
                // Update UI
                document.querySelectorAll('.password-requirements li').forEach(li => {
                    li.className = '';
                });
            } else {
                showNotification('❌ ' + (result.message || 'Şifre değiştirilemedi'), 'error');
            }
            
        } catch (error) {
            console.error('Password change error:', error);
            showNotification('❌ Şifre değiştirilirken hata oluştu', 'error');
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

<?php include FRONTEND_DIR . '/components/footer.php'; ?>