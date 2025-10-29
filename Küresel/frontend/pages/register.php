<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol - Küresel Etki Zinciri</title>
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
    
    <main class="register-page">
        <div class="container">
            <div class="auth-container">
                <div class="auth-card">
                    <div class="auth-header">
                        <h1>Kayıt Ol</h1>
                        <p>Küresel Etki Zinciri platformuna katılın ve sürdürülebilir geleceğin parçası olun</p>
                    </div>
                    
                    <!-- User Type Selection -->
                    <div class="user-type-selection">
                        <h3>Hesap Türünüz</h3>
                        <div class="user-type-grid">
                            <label class="user-type-card">
                                <input type="radio" name="user_type" value="company" required>
                                <div class="card-content">
                                    <div class="card-icon">🏢</div>
                                    <h4>Şirket</h4>
                                    <p>Tedarik zinciri şeffaflığı sağlamak isteyen şirketler</p>
                                </div>
                            </label>
                            
                            <label class="user-type-card">
                                <input type="radio" name="user_type" value="validator" required>
                                <div class="card-content">
                                    <div class="card-icon">✅</div>
                                    <h4>Validator</h4>
                                    <p>Bağımsız denetim ve doğrulama hizmeti veren organizasyonlar</p>
                                </div>
                            </label>
                            
                            <label class="user-type-card">
                                <input type="radio" name="user_type" value="consumer" required>
                                <div class="card-content">
                                    <div class="card-icon">👤</div>
                                    <h4>Tüketici</h4>
                                    <p>Ürün bilgilerine erişmek isteyen bireysel kullanıcılar</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <form id="register-form" class="auth-form" data-api="/auth/register" data-redirect="/login">
                        <!-- Personal Information -->
                        <div class="form-section">
                            <h3>Kişisel Bilgiler</h3>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="first_name">Ad *</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control" required 
                                           placeholder="Adınız">
                                </div>
                                
                                <div class="form-group">
                                    <label for="last_name">Soyad *</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control" required 
                                           placeholder="Soyadınız">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">E-posta Adresi *</label>
                                <input type="email" id="email" name="email" class="form-control" required 
                                       placeholder="ornek@email.com">
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Telefon</label>
                                <input type="tel" id="phone" name="phone" class="form-control" 
                                       placeholder="+90 5XX XXX XX XX">
                            </div>
                        </div>
                        
                        <!-- Password Section -->
                        <div class="form-section">
                            <h3>Güvenlik</h3>
                            
                            <div class="form-group">
                                <label for="password">Şifre *</label>
                                <div class="password-input">
                                    <input type="password" id="password" name="password" class="form-control" required 
                                           placeholder="En az 8 karakter" minlength="8">
                                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                        👁️
                                    </button>
                                </div>
                                <div class="password-strength" id="password-strength"></div>
                            </div>
                            
                            <div class="form-group">
                                <label for="password_confirm">Şifre Tekrar *</label>
                                <div class="password-input">
                                    <input type="password" id="password_confirm" name="password_confirm" class="form-control" required 
                                           placeholder="Şifrenizi tekrar girin">
                                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirm')">
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
                        </div>
                        
                        <!-- Additional Info Section (Dynamic based on user type) -->
                        <div class="form-section" id="additional-info" style="display: none;">
                            <!-- Content will be dynamically added based on user type -->
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="form-section">
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="terms_accepted" required>
                                    <span class="checkmark"></span>
                                    <a href="/Küresel/?page=terms" target="_blank">Kullanım Şartları</a>'nı okudum ve kabul ediyorum *
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="privacy_accepted" required>
                                    <span class="checkmark"></span>
                                    <a href="/Küresel/?page=privacy" target="_blank">Gizlilik Politikası</a>'nı okudum ve kabul ediyorum *
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="marketing_consent">
                                    <span class="checkmark"></span>
                                    Pazarlama e-postalarını almak istiyorum
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-large auth-submit">
                            🚀 Hesap Oluştur
                        </button>
                        
                        <input type="hidden" name="csrf_token" value="<?= $auth->generateCSRFToken() ?>">
                        <input type="hidden" id="selected_user_type" name="user_type" value="">
                    </form>
                    
                    <div class="auth-footer">
                        <p>Zaten hesabınız var mı? <a href="/Küresel/?page=login">Giriş yapın</a></p>
                    </div>
                </div>
                
                <!-- Info Panel -->
                <div class="auth-info">
                    <h2>Neden Bize Katılmalısınız?</h2>
                    <div class="info-benefits">
                        <div class="benefit-item">
                            <div class="benefit-icon">🌱</div>
                            <h3>Sürdürülebilirlik</h3>
                            <p>Çevresel ve sosyal etkilerinizi ölçün ve iyileştirin</p>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">🔗</div>
                            <h3>Şeffaflık</h3>
                            <p>Tedarik zincirinizin her adımını şeffaf şekilde paylaşın</p>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">🏆</div>
                            <h3>Rekabet Avantajı</h3>
                            <p>Sürdürülebilirlik odaklı rekabette öne geçin</p>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">🤝</div>
                            <h3>Güven</h3>
                            <p>Tüketicilerle güven temelli ilişkiler kurun</p>
                        </div>
                    </div>
                    
                    <div class="testimonial">
                        <blockquote>
                            "Küresel Etki Zinciri sayesinde müşterilerimiz ürünlerimizin hikayesini öğrenebiliyor ve sürdürülebilirlik çabalarımızı görebiliyor."
                        </blockquote>
                        <cite>- Örnek Şirket CEO'su</cite>
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
        const userTypeRadios = document.querySelectorAll('input[name="user_type"]');
        const selectedUserType = document.getElementById('selected_user_type');
        const additionalInfo = document.getElementById('additional-info');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirm');
        const registerForm = document.getElementById('register-form');
        
        // User type selection handling
        userTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                selectedUserType.value = this.value;
                showAdditionalFields(this.value);
            });
        });
        
        function showAdditionalFields(userType) {
            let additionalHTML = '';
            
            switch(userType) {
                case 'company':
                    additionalHTML = `
                        <h3>Şirket Bilgileri</h3>
                        <div class="form-group">
                            <label for="company_name">Şirket Adı *</label>
                            <input type="text" id="company_name" name="company_name" class="form-control" required 
                                   placeholder="Şirket adınız">
                        </div>
                        <div class="form-group">
                            <label for="company_type">Şirket Türü *</label>
                            <select id="company_type" name="company_type" class="form-control" required>
                                <option value="">Seçiniz</option>
                                <option value="supplier">Tedarikçi</option>
                                <option value="manufacturer">Üretici</option>
                                <option value="distributor">Distribütör</option>
                                <option value="retailer">Perakendeci</option>
                                <option value="farmer">Çiftçi</option>
                                <option value="logistics">Lojistik</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="industry_sector">Sektör</label>
                            <select id="industry_sector" name="industry_sector" class="form-control">
                                <option value="">Seçiniz</option>
                                <option value="food">Gıda</option>
                                <option value="textile">Tekstil</option>
                                <option value="cosmetics">Kozmetik</option>
                                <option value="electronics">Elektronik</option>
                                <option value="automotive">Otomotiv</option>
                                <option value="other">Diğer</option>
                            </select>
                        </div>
                    `;
                    break;
                    
                case 'validator':
                    additionalHTML = `
                        <h3>Validator Bilgileri</h3>
                        <div class="form-group">
                            <label for="validator_name">Organizasyon Adı *</label>
                            <input type="text" id="validator_name" name="validator_name" class="form-control" required 
                                   placeholder="Organizasyon/kurum adınız">
                        </div>
                        <div class="form-group">
                            <label for="organization_type">Organizasyon Türü *</label>
                            <select id="organization_type" name="organization_type" class="form-control" required>
                                <option value="">Seçiniz</option>
                                <option value="ngo">STK</option>
                                <option value="certification_body">Sertifikasyon Kuruluşu</option>
                                <option value="audit_firm">Denetim Firması</option>
                                <option value="government">Devlet Kurumu</option>
                                <option value="independent">Bağımsız Uzman</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="specialization">Uzmanlık Alanları</label>
                            <div class="checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="specialization[]" value="environmental">
                                    <span class="checkmark"></span>
                                    Çevresel
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="specialization[]" value="social">
                                    <span class="checkmark"></span>
                                    Sosyal
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="specialization[]" value="labor_rights">
                                    <span class="checkmark"></span>
                                    İşçi Hakları
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="specialization[]" value="organic">
                                    <span class="checkmark"></span>
                                    Organik
                                </label>
                            </div>
                        </div>
                    `;
                    break;
                    
                case 'consumer':
                    additionalHTML = `
                        <h3>İlgi Alanları</h3>
                        <div class="form-group">
                            <label>Hangi konularda bilgi almak istiyorsunuz?</label>
                            <div class="checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="interests[]" value="environmental">
                                    <span class="checkmark"></span>
                                    Çevresel Etki
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="interests[]" value="social">
                                    <span class="checkmark"></span>
                                    Sosyal Sorumluluk
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="interests[]" value="organic">
                                    <span class="checkmark"></span>
                                    Organik Ürünler
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="interests[]" value="fair_trade">
                                    <span class="checkmark"></span>
                                    Adil Ticaret
                                </label>
                            </div>
                        </div>
                    `;
                    break;
            }
            
            if (additionalHTML) {
                additionalInfo.innerHTML = additionalHTML;
                additionalInfo.style.display = 'block';
            } else {
                additionalInfo.style.display = 'none';
            }
        }
        
        // Password validation
        passwordInput.addEventListener('input', function() {
            updatePasswordRequirements(this.value, confirmPasswordInput.value);
        });
        
        confirmPasswordInput.addEventListener('input', function() {
            updatePasswordRequirements(passwordInput.value, this.value);
        });
        
        // Form submission
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validate passwords match
            if (passwordInput.value !== confirmPasswordInput.value) {
                showNotification('❌ Şifreler eşleşmiyor', 'error');
                return;
            }
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // Handle checkbox arrays
            const checkboxArrays = ['specialization', 'interests'];
            checkboxArrays.forEach(field => {
                const values = formData.getAll(field + '[]');
                if (values.length > 0) {
                    data[field] = values;
                }
            });
            
            const submitBtn = this.querySelector('.auth-submit');
            const originalText = submitBtn.textContent;
            
            try {
                submitBtn.disabled = true;
                submitBtn.textContent = '⏳ Hesap oluşturuluyor...';
                
                const response = await fetch('/Küresel/api/v1/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        first_name: firstName,
                        last_name: lastName,
                        email: email,
                        password: password,
                        user_type: userType,
                        terms_accepted: termsAccepted
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('✅ Hesabınız başarıyla oluşturuldu! Giriş sayfasına yönlendiriliyorsunuz...', 'success');
                    // Use redirect from API response if available, otherwise fallback to default
                    const redirectUrl = (result.data && result.data.redirect) ? result.data.redirect : '/Küresel/?page=login';
                    setTimeout(() => {
                        window.location.href = redirectUrl;
                    }, 2000);
                } else {
                    showNotification('❌ ' + (result.message || 'Kayıt başarısız'), 'error');
                }
                
            } catch (error) {
                console.error('Registration error:', error);
                showNotification('❌ Kayıt sırasında hata oluştu', 'error');
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
        }, 6000);
    }
    </script>
</body>
</html>