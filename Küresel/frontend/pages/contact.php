<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim - Küresel Etki Zinciri</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include FRONTEND_DIR . '/components/header.php'; ?>
    
    <main class="contact-page">
        <div class="container">
            <!-- Hero Section -->
            <section class="page-hero">
                <h1>İletişim</h1>
                <p class="page-subtitle">Bizimle iletişime geçin, sorularınızı yanıtlayalım</p>
            </section>

            <div class="contact-content">
                <!-- Contact Form -->
                <section class="contact-form-section">
                    <div class="form-container">
                        <h2>Bize Ulaşın</h2>
                        <form id="contact-form" class="contact-form" data-api="/contact/send" method="POST">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="first_name">Ad *</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="last_name">Soyad *</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">E-posta *</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Telefon</label>
                                <input type="tel" id="phone" name="phone" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="company">Şirket/Organizasyon</label>
                                <input type="text" id="company" name="company" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="subject">Konu *</label>
                                <select id="subject" name="subject" class="form-control" required>
                                    <option value="">Konu seçin</option>
                                    <option value="general">Genel Bilgi</option>
                                    <option value="partnership">Ortaklık</option>
                                    <option value="technical">Teknik Destek</option>
                                    <option value="validator">Validator Olmak</option>
                                    <option value="company">Şirket Kaydı</option>
                                    <option value="press">Basın</option>
                                    <option value="other">Diğer</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Mesajınız *</label>
                                <textarea id="message" name="message" class="form-control" rows="6" required placeholder="Mesajınızı buraya yazın..."></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="privacy_consent" required>
                                    <span class="checkmark"></span>
                                    <a href="/privacy" target="_blank">Gizlilik Politikası</a>'nı okudum ve kabul ediyorum *
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-large">
                                📨 Mesajı Gönder
                            </button>
                        </form>
                    </div>
                </section>

                <!-- Contact Info -->
                <section class="contact-info-section">
                    <div class="info-container">
                        <h2>İletişim Bilgileri</h2>
                        
                        <div class="contact-methods">
                            <div class="contact-method">
                                <div class="method-icon">📧</div>
                                <div class="method-info">
                                    <h3>E-posta</h3>
                                    <p><a href="mailto:info@kuresaletzinciri.com">info@kuresaletzinciri.com</a></p>
                                    <p class="method-note">24 saat içinde yanıt veriyoruz</p>
                                </div>
                            </div>
                            
                            <div class="contact-method">
                                <div class="method-icon">📞</div>
                                <div class="method-info">
                                    <h3>Telefon</h3>
                                    <p><a href="tel:+902121234567">+90 (212) 123 45 67</a></p>
                                    <p class="method-note">Pazartesi - Cuma, 09:00 - 18:00</p>
                                </div>
                            </div>
                            
                            <div class="contact-method">
                                <div class="method-icon">📍</div>
                                <div class="method-info">
                                    <h3>Adres</h3>
                                    <p>Maslak Mahallesi<br>
                                    Teknoloji Caddesi No: 123<br>
                                    Sarıyer, İstanbul 34485</p>
                                </div>
                            </div>
                            
                            <div class="contact-method">
                                <div class="method-icon">🌐</div>
                                <div class="method-info">
                                    <h3>Sosyal Medya</h3>
                                    <div class="social-links">
                                        <a href="#" class="social-link">LinkedIn</a>
                                        <a href="#" class="social-link">Twitter</a>
                                        <a href="#" class="social-link">GitHub</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Office Hours -->
                        <div class="office-hours">
                            <h3>Çalışma Saatleri</h3>
                            <div class="hours-list">
                                <div class="hours-item">
                                    <span class="day">Pazartesi - Cuma</span>
                                    <span class="time">09:00 - 18:00</span>
                                </div>
                                <div class="hours-item">
                                    <span class="day">Cumartesi</span>
                                    <span class="time">10:00 - 15:00</span>
                                </div>
                                <div class="hours-item">
                                    <span class="day">Pazar</span>
                                    <span class="time">Kapalı</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- FAQ Section -->
            <section class="faq-section">
                <h2 class="section-title">Sık Sorulan Sorular</h2>
                <div class="faq-grid">
                    <div class="faq-item">
                        <h3>Platform nasıl çalışır?</h3>
                        <p>Şirketler tedarik zinciri verilerini sisteme girer, bağımsız validatörler bu verileri doğrular ve tüketiciler QR kod tarayarak ürün bilgilerine ulaşır.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3>Şirket olarak nasıl katılabilirim?</h3>
                        <p>Kayıt formunu doldurarak başvurabilirsiniz. Başvurunuz incelendikten sonra platforma erişim sağlanır.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3>Validator olmak için gereksinimler neler?</h3>
                        <p>İlgili sektörde deneyim, bağımsızlık ve gerekli sertifikalar validator olmak için temel gereksinimlerdir.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3>Platform ücretsiz mi?</h3>
                        <p>Temel QR kod tarama ücretsizdir. Şirketler için farklı paketler mevcuttur. Detaylı bilgi için iletişime geçin.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3>Veri güvenliği nasıl sağlanır?</h3>
                        <p>Blockchain teknolojisi ile veriler değiştirilemez şekilde saklanır ve kriptografik yöntemlerle korunur.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h3>Hangi sektörler desteklenir?</h3>
                        <p>Gıda, tekstil, kozmetik, elektronik ve daha pek çok sektörden şirketler platformumuzu kullanabilir.</p>
                    </div>
                </div>
            </section>

            <!-- Map Section -->
            <section class="map-section">
                <h2 class="section-title">Ofisimiz</h2>
                <div class="map-container">
                    <div class="map-placeholder">
                        <div class="map-content">
                            <div class="map-icon">🗺️</div>
                            <h3>İstanbul Ofisi</h3>
                            <p>Maslak Mahallesi, Teknoloji Caddesi No: 123<br>
                            Sarıyer, İstanbul 34485</p>
                            <a href="https://maps.google.com/?q=Maslak+Teknoloji+Caddesi" target="_blank" class="btn btn-outline">
                                📍 Haritada Göster
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <?php include FRONTEND_DIR . '/components/footer.php'; ?>
    
    <script src="assets/js/main.js"></script>
    
    <script>
    // Contact form handling
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contact-form');
        
        if (contactForm) {
            contactForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());
                
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                
                try {
                    submitBtn.disabled = true;
                    submitBtn.textContent = '📤 Gönderiliyor...';
                    
                    // Simulate form submission (replace with actual API call)
                    await new Promise(resolve => setTimeout(resolve, 2000));
                    
                    // Show success message
                    showNotification('Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapacağız.', 'success');
                    
                    // Reset form
                    this.reset();
                    
                } catch (error) {
                    showNotification('Mesaj gönderimi sırasında hata oluştu. Lütfen tekrar deneyin.', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            });
        }
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