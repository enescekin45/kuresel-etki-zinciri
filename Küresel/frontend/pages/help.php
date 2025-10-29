<?php
include FRONTEND_DIR . '/components/header.php';
?>
<link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
<main class="page-content">
    <div class="container">
        <div class="page-header">
            <h1>❓ Yardım Merkezi</h1>
            <p>Küresel Etki Zinciri platformu hakkında sıkça sorulan sorular ve yardım kaynakları</p>
        </div>
        
        <div class="help-content">
            <section class="help-section">
                <h2>📱 QR Kod Tarama</h2>
                
                <div class="faq-item">
                    <h3>🔍 QR kodu nasıl tararım?</h3>
                    <p>Platformdaki "QR Kod Tara" butonuna tıklayın ve kameranızı ürün üzerindeki QR koda doğrultun. 
                    Sistem otomatik olarak kodu tanıyacak ve ürün bilgilerini gösterecektir.</p>
                    <div class="help-tip">
                        <strong>💡 İpucu:</strong> Işıklandırma yeterli değilse telefonunuzun flaşını açmayı deneyin.
                    </div>
                </div>
                
                <div class="faq-item">
                    <h3>🚫 QR kod okunmuyor, ne yapmalıyım?</h3>
                    <p>Kodun net ve zarar görmemiş olduğundan emin olun. Işıklandırma yeterli değilse 
                    telefonunuzun flaşını açmayı deneyin. Sorun devam ederse bize ulaşın.</p>
                    <div class="troubleshooting-steps">
                        <ol>
                            <li>Kodun net ve zarar görmemiş olduğundan emin olun</li>
                            <li>Işıklandırma yeterli değilse flaşı açın</li>
                            <li>Uygulamayı yeniden başlatın</li>
                            <li>Bize ulaşın: support@kuresaletzinciri.com</li>
                        </ol>
                    </div>
                </div>
                
                <div class="faq-item">
                    <h3>🔄 QR kodu taradım ama bilgiler güncellenmedi, neden?</h3>
                    <p>Bazı durumlarda bilgilerin güncellenmesi birkaç dakika sürebilir. 
                    Sayfayı yenilemeyi deneyin. Sorun devam ederse bize ulaşın.</p>
                </div>
            </section>
            
            <section class="help-section">
                <h2>👤 Hesap Yönetimi</h2>
                
                <div class="faq-item">
                    <h3>🔑 Şifremi unuttum, ne yapmalıyım?</h3>
                    <p>Giriş sayfasındaki "Şifremi unuttum" bağlantısına tıklayın ve e-posta adresinizi girin. 
                    Şifre sıfırlama bağlantısı e-posta adresinize gönderilecektir.</p>
                    <div class="help-tip">
                        <strong>💡 İpucu:</strong> Spam/junk klasörünü kontrol etmeyi unutmayın.
                    </div>
                </div>
                
                <div class="faq-item">
                    <h3>✏️ Profil bilgilerimi nasıl güncelleyebilirim?</h3>
                    <p>Sağ üst köşedeki profil menüsünden "Profil" veya "Ayarlar" seçeneğine tıklayarak 
                    profil bilgilerinizi güncelleyebilirsiniz.</p>
                </div>
                
                <div class="faq-item">
                    <h3>🚪 Hesabımı nasıl silebilirim?</h3>
                    <p>Ayarlar sayfasında "Hesabı Sil" bölümünü bulabilir ve oradan hesabınızı silebilirsiniz. 
                    Bu işlem geri alınamaz.</p>
                </div>
            </section>
            
            <section class="help-section">
                <h2>📦 Ürün Bilgileri</h2>
                
                <div class="faq-item">
                    <h3>📊 Ürün bilgileri nereden geliyor?</h3>
                    <p>Ürün bilgileri, şirketler tarafından platforma yüklenen ve 
                    bağımsız doğrulayıcılar tarafından onaylanan verilerden oluşur.</p>
                </div>
                
                <div class="faq-item">
                    <h3>🚩 Bir ürün hakkında yanlış bilgi varsa ne yapmalıyım?</h3>
                    <p>Ürün sayfasındaki "Bildir" butonu aracılığıyla yanlış bilgileri bize bildirebilirsiniz. 
                    Ekibimiz gerekli incelemeyi yapacak ve bilgileri güncelleyecektir.</p>
                </div>
                
                <div class="faq-item">
                    <h3>➕ Yeni bir ürün eklemek istiyorum, nasıl yapabilirim?</h3>
                    <p>Şirket kullanıcısıysanız, şirket panelinden yeni ürün ekleyebilirsiniz. 
                    Tüketici kullanıcısıysanız, bize info@kuresaletzinciri.com adresinden ulaşabilirsiniz.</p>
                </div>
            </section>
            
            <section class="help-section">
                <h2>📬 İletişim</h2>
                <p>Diğer sorularınız için destek ekibimizle iletişime geçin:</p>
                <p>📧 Email: support@kuresaletzinciri.com</p>
                <p>📞 Telefon: +90 212 123 4567</p>
                <p>⏰ Çalışma Saatleri: Pazartesi-Cuma 09:00-18:00</p>
                <div class="contact-buttons">
                    <a href="/Küresel/?page=contact" class="btn btn-primary">✉️ İletişim Formu</a>
                </div>
            </section>
        </div>
    </div>
</main>

<?php include FRONTEND_DIR . '/components/footer.php'; ?>