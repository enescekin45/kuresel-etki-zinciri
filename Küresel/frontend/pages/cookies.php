<?php
include FRONTEND_DIR . '/components/header.php';
?>
<link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
<main class="page-content">
    <div class="container">
        <div class="page-header">
            <h1>🍪 Çerez Politikası</h1>
            <p>Son güncelleme: 10 Ekim 2025</p>
        </div>
        
        <div class="cookies-content">
            <section class="cookies-section">
                <h2>👋 Giriş</h2>
                <p>Küresel Etki Zinciri olarak, platformumuzda çerezler kullanmaktayız. 
                Bu politika, hangi çerezleri kullandığımızı ve neden kullandığımızı açıklar.</p>
                
                <div class="cookie-summary">
                    <h3>📋 Çerez Kullanım Amacı</h3>
                    <ul>
                        <li>👤 Kullanıcı deneyimini kişiselleştirme</li>
                        <li>⚙️ Platformun düzgün çalışmasını sağlama</li>
                        <li>📊 Kullanım istatistiklerini toplama</li>
                        <li>🔒 Güvenlik önlemlerini uygulama</li>
                    </ul>
                </div>
            </section>
            
            <section class="cookies-section">
                <h2>❓ Çerez Nedir?</h2>
                <p>Çerezler, web sitesini ziyaret ettiğinizde tarayıcınız tarafından 
                cihazınıza yerleştirilen küçük metin dosyalarıdır. Bu dosyalar, 
                kullanıcı tercihlerini hatırlamak ve platform deneyimini iyileştirmek için kullanılır.</p>
                
                <div class="cookie-types">
                    <h3>🍪 Çerez Türleri</h3>
                    <ul>
                        <li><strong>Oturum çerezleri:</strong> Oturum süresince geçerli</li>
                        <li><strong>Kalıcı çerezler:</strong> Belirli süre boyunca cihazda kalır</li>
                        <li><strong>İlk taraf çerezler:</strong> Bizim tarafımızdan yerleştirilir</li>
                        <li><strong>Üçüncü taraf çerezler:</strong> Ortaklarımız tarafından yerleştirilir</li>
                    </ul>
                </div>
            </section>
            
            <section class="cookies-section">
                <h2>🔧 Kullandığımız Çerezler</h2>
                
                <h3>🔒 Gerekli Çerezler</h3>
                <p>Bu çerezler, platformun temel işlevleri için gereklidir ve devre dışı bırakılamaz:</p>
                <div class="cookie-list">
                    <ul>
                        <li><strong>session_id:</strong> Kullanıcı oturumunu yönetmek için</li>
                        <li><strong>csrf_token:</strong> Güvenlik için CSRF koruması</li>
                        <li><strong>cookie_consent:</strong> Çerez tercihlerinizi hatırlamak için</li>
                    </ul>
                </div>
                
                <h3>📊 Performans Çerezleri</h3>
                <p>Bu çerezler, platformun nasıl kullanıldığını anlamamıza yardımcı olur:</p>
                <div class="cookie-list">
                    <ul>
                        <li><strong>_ga:</strong> Google Analytics - Ziyaretçi sayımı</li>
                        <li><strong>_gid:</strong> Google Analytics - Ziyaretçi sayımı</li>
                        <li><strong>performance_metrics:</strong> Platform performansı</li>
                    </ul>
                </div>
                
                <h3>🎯 Fonksiyonel Çerezler</h3>
                <p>Bu çerezler, platformun gelişmiş özelliklerini sunmak için kullanılır:</p>
                <div class="cookie-list">
                    <ul>
                        <li><strong>language:</strong> Dil tercihinizi hatırlamak için</li>
                        <li><strong>theme:</strong> Tema tercihinizi hatırlamak için</li>
                        <li><strong>preferences:</strong> Kullanıcı tercihlerini saklamak için</li>
                    </ul>
                </div>
                
                <h3>📈 Pazarlama Çerezleri</h3>
                <p>Bu çerezler, ilgili reklamlar göstermek için kullanılır:</p>
                <div class="cookie-list">
                    <ul>
                        <li><strong>_fbp:</strong> Facebook Pixel - Reklam performansı</li>
                        <li><strong>_gcl_au:</strong> Google Ads - Reklam dönüşümü</li>
                    </ul>
                </div>
            </section>
            
            <section class="cookies-section">
                <h2>⚙️ Çerez Yönetimi</h2>
                <p>Tarayıcınızın ayarlarını kullanarak çerezleri yönetebilir veya devre dışı bırakabilirsiniz. 
                Ancak bazı çerezlerin devre dışı bırakılması platformun düzgün çalışmamasına neden olabilir.</p>
                
                <div class="cookie-controls">
                    <h3>🎛️ Çerez Kontrolü</h3>
                    <p>Çerez tercihlerinizi aşağıdaki butonlarla yönetebilirsiniz:</p>
                    <div class="control-buttons">
                        <button class="btn btn-primary" onclick="showCookieSettings()">🔧 Çerez Ayarlarını Yönet</button>
                        <button class="btn btn-outline" onclick="acceptAllCookies()">✅ Tüm Çerezleri Kabul Et</button>
                        <button class="btn btn-outline" onclick="rejectNonEssentialCookies()">❌ Gerekli Çerezler Hariç Reddet</button>
                    </div>
                </div>
                
                <h3>🌐 Tarayıcı Ayarları</h3>
                <p>Çoğu tarayıcı, çerezleri otomatik olarak kabul eder. Bu ayarı değiştirmek için:</p>
                <div class="browser-instructions">
                    <ul>
                        <li><strong>Chrome:</strong> Ayarlar > Gizlilik ve güvenlik > Çerezler ve diğer site verileri</li>
                        <li><strong>Firefox:</strong> Ayarlar > Gizlilik ve Güvenlik > Çerezler ve Site Verileri</li>
                        <li><strong>Safari:</strong> Tercihler > Gizlilik > Çerezler ve web sitesi verileri</li>
                        <li><strong>Edge:</strong> Ayarlar > Çerezler ve site izinleri</li>
                    </ul>
                </div>
            </section>
            
            <section class="cookies-section">
                <h2>🤝 Üçüncü Taraf Çerezler</h2>
                <p>Platformumuzda, aşağıdaki üçüncü taraf hizmetleri çerezler kullanabilir:</p>
                <div class="third-party-cookies">
                    <ul>
                        <li><strong>Google Analytics:</strong> Platform analizi için</li>
                        <li><strong>Facebook Pixel:</strong> Reklam hedefleme için</li>
                        <li><strong>Google Ads:</strong> Reklam dönüşüm ölçümü için</li>
                        <li><strong>Hotjar:</strong> Kullanıcı deneyimi analizi için</li>
                    </ul>
                </div>
                
                <h3>🔒 Üçüncü Taraf Gizlilik Politikaları</h3>
                <p>Bu hizmetlerin kendi gizlilik politikaları bulunmaktadır:</p>
                <ul>
                    <li><a href="https://policies.google.com/privacy" target="_blank">Google Gizlilik Politikası</a></li>
                    <li><a href="https://www.facebook.com/policy.php" target="_blank">Facebook Gizlilik Politikası</a></li>
                    <li><a href="https://www.hotjar.com/legal/policies/privacy" target="_blank">Hotjar Gizlilik Politikası</a></li>
                </ul>
            </section>
            
            <section class="cookies-section">
                <h2>🔄 Politika Değişiklikleri</h2>
                <p>Bu çerez politikasını herhangi bir zamanda güncelleyebiliriz. 
                Değişiklikler bu sayfada yayınlanarak yapılır.</p>
                
                <div class="policy-updates">
                    <h3>📅 Güncelleme Süreci</h3>
                    <p>Politika değişikliklerinde:</p>
                    <ul>
                        <li>Kullanıcıları önceden bilgilendiririz</li>
                        <li>Değişiklik tarihini güncelleriz</li>
                        <li>Gerekli durumlarda açık onay alırız</li>
                    </ul>
                </div>
            </section>
            
            <section class="cookies-section">
                <h2>📬 İletişim</h2>
                <p>Çerez politikası ile ilgili sorularınız varsa, aşağıdaki yollarla bizimle iletişime geçin:</p>
                <p>📧 Email: privacy@kuresaletzinciri.com</p>
                <p>📞 Telefon: +90 212 123 4567</p>
                <p>📍 Adres: Levent Mahallesi, Şehit Reşat Sk. No:12, 34330 Beşiktaş/İstanbul</p>
            </section>
        </div>
    </div>
</main>

<script>
function showCookieSettings() {
    alert('🔧 Çerez ayarları paneli açılacak. Bu özellik yakında eklenecek.');
}

function acceptAllCookies() {
    alert('✅ Tüm çerezler kabul edildi.');
}

function rejectNonEssentialCookies() {
    alert('❌ Gerekli olmayan çerezler reddedildi.');
}
</script>

<?php include FRONTEND_DIR . '/components/footer.php'; ?>