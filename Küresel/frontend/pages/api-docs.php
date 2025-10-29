<?php
include FRONTEND_DIR . '/components/header.php';
?>

<link rel="stylesheet" href="assets/css/dashboard.css">

<main class="page-content dashboard">
    <div class="container">
        <div class="dashboard-header">
            <h1>📚 API Dokümantasyonu</h1>
            <p class="subtitle">Küresel Etki Zinciri API'sini kullanmaya başlayın</p>
        </div>
        
        <div class="dashboard-grid">
            <div class="dashboard-card full-width">
                <div class="card-header">
                    <h2>👋 Giriş</h2>
                </div>
                <div class="card-content">
                    <p>Küresel Etki Zinciri API'si, platformumuzun sunduğu tüm işlevleri programatik olarak 
                    erişmenizi sağlar. Bu dokümantasyon, API uç noktalarını, kimlik doğrulama yöntemlerini 
                    ve kullanım örneklerini içerir.</p>
                    
                    <div class="api-status">
                        <h3>📊 API Durumu</h3>
                        <p>🟢 API şu anda aktif ve çalışıyor</p>
                        <p>⏱️ Son güncelleme: 28 Ekim 2025</p>
                    </div>
                    
                    <div class="endpoint-info">
                        <h3>📍 API Ana URL</h3>
                        <pre><code>http://localhost/Küresel/api</code></pre>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card full-width">
                <div class="card-header">
                    <h2>🔐 Kimlik Doğrulama</h2>
                </div>
                <div class="card-content">
                    <p>API'ye erişim için kimlik doğrulama gereklidir. Kimlik doğrulama, 
                    HTTP başlıklarında geçerli bir API anahtarı ile yapılır.</p>
                    
                    <div class="endpoint">
                        <h3>🔑 API Anahtarı</h3>
                        <p>API anahtarınızı dashboard üzerinden oluşturabilirsiniz:</p>
                        <ol>
                            <li>Profil menüsünden "Ayarlar" seçeneğine tıklayın</li>
                            <li>"Geliştirici Ayarları" sekmesine gidin</li>
                            <li>"Yeni API Anahtarı Oluştur" butonuna tıklayın</li>
                        </ol>
                    </div>
                    
                    <div class="endpoint">
                        <h3>📬 Kimlik Doğrulama Başlığı</h3>
                        <p>Her API isteğine aşağıdaki başlığı eklemelisiniz:</p>
                        <pre><code>Authorization: Bearer YOUR_API_KEY</code></pre>
                    </div>
                    
                    <div class="endpoint">
                        <h3>🔄 Rate Limiting</h3>
                        <p>API kullanımı için rate limiting uygulanmaktadır:</p>
                        <ul>
                            <li>1000 istek/saat/IP adresi</li>
                            <li>Aşıldığında 429 HTTP kodu döner</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>📦 Ürün Uç Noktaları</h2>
                </div>
                <div class="card-content">
                    <div class="endpoint">
                        <h3>📋 GET /products/list</h3>
                        <p>Ürün listesini getirir</p>
                        <h4>Parametreler:</h4>
                        <ul>
                            <li><strong>limit</strong> (opsiyonel): Sayfa başına ürün sayısı (varsayılan: 20, maksimum: 100)</li>
                            <li><strong>page</strong> (opsiyonel): Sayfa numarası (varsayılan: 1)</li>
                        </ul>
                        <h4>Örnek İstek:</h4>
                        <pre><code>curl -H "Authorization: Bearer YOUR_API_KEY" \
  "http://localhost/Küresel/api/products/list?limit=10&page=1"</code></pre>
                    </div>
                    
                    <div class="endpoint">
                        <h3>🔍 GET /products/get</h3>
                        <p>Belirli bir ürünün detaylarını getirir</p>
                        <h4>Parametreler:</h4>
                        <ul>
                            <li><strong>uuid</strong> (gerekli): Ürünün benzersiz tanımlayıcısı</li>
                        </ul>
                        <h4>Örnek İstek:</h4>
                        <pre><code>curl -H "Authorization: Bearer YOUR_API_KEY" \
  "http://localhost/Küresel/api/products/get?uuid=a1b2c3d4-e5f6-7890-g1h2-i3j4k5l6m7n8"</code></pre>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>🏢 Şirket Uç Noktaları</h2>
                </div>
                <div class="card-content">
                    <div class="endpoint">
                        <h3>📊 GET /company/stats/products</h3>
                        <p>Şirketin ürün istatistiklerini getirir</p>
                    </div>
                    
                    <div class="endpoint">
                        <h3>📊 GET /company/stats/scans</h3>
                        <p>Şirketin tarama istatistiklerini getirir</p>
                    </div>
                    
                    <div class="endpoint">
                        <h3>📊 GET /company/stats/validation</h3>
                        <p>Şirketin doğrulama istatistiklerini getirir</p>
                    </div>
                    
                    <div class="endpoint">
                        <h3>📊 GET /company/stats/pending</h3>
                        <p>Şirketin bekleyen doğrulama istatistiklerini getirir</p>
                    </div>
                    
                    <div class="endpoint">
                        <h3>📋 GET /company/products/recent</h3>
                        <p>Şirketin son ürün listesini getirir</p>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>✅ Doğrulayıcı Uç Noktaları</h2>
                </div>
                <div class="card-content">
                    <div class="endpoint">
                        <h3>📊 GET /validator/stats/total</h3>
                        <p>Doğrulayıcının toplam doğrulama sayısını getirir</p>
                    </div>
                    
                    <div class="endpoint">
                        <h3>📊 GET /validator/stats/approved</h3>
                        <p>Doğrulayıcının onaylanan doğrulama sayısını getirir</p>
                    </div>
                    
                    <div class="endpoint">
                        <h3>📊 GET /validator/stats/rejected</h3>
                        <p>Doğrulayıcının reddedilen doğrulama sayısını getirir</p>
                    </div>
                    
                    <div class="endpoint">
                        <h3>📊 GET /validator/stats/pending</h3>
                        <p>Doğrulayıcının bekleyen doğrulama sayısını getirir</p>
                    </div>
                    
                    <div class="endpoint">
                        <h3>📋 GET /validator/validations/pending</h3>
                        <p>Doğrulayıcının bekleyen doğrulamalarını getirir</p>
                    </div>
                    
                    <div class="endpoint">
                        <h3>🕒 GET /validator/activities/recent</h3>
                        <p>Doğrulayıcının son aktivitelerini getirir</p>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>👮 Admin Uç Noktaları</h2>
                </div>
                <div class="card-content">
                    <div class="endpoint">
                        <h3>📊 GET /admin/stats/users</h3>
                        <p>Admin kullanıcı istatistiklerini getirir</p>
                    </div>
                    
                    <div class="endpoint">
                        <h3>📊 GET /admin/stats/companies</h3>
                        <p>Admin şirket istatistiklerini getirir</p>
                    </div>
                    
                    <div class="endpoint">
                        <h3>📊 GET /admin/stats/validators</h3>
                        <p>Admin doğrulayıcı istatistiklerini getirir</p>
                    </div>
                    
                    <div class="endpoint">
                        <h3>📊 GET /admin/stats/products</h3>
                        <p>Admin ürün istatistiklerini getirir</p>
                    </div>
                    
                    <div class="endpoint">
                        <h3>📊 GET /admin/stats/overview</h3>
                        <p>Admin genel istatistikleri getirir</p>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card full-width">
                <div class="card-header">
                    <h2>🔄 Hata Kodları</h2>
                </div>
                <div class="card-content">
                    <div class="error-codes">
                        <div class="error-code">
                            <h3>400 - Geçersiz İstek</h3>
                            <p>İstek formatı geçersiz veya gerekli parametreler eksik</p>
                        </div>
                        
                        <div class="error-code">
                            <h3>401 - Yetkilendirme Hatası</h3>
                            <p>Geçersiz veya eksik API anahtarı</p>
                        </div>
                        
                        <div class="error-code">
                            <h3>403 - Erişim Engellendi</h3>
                            <p>İstenen kaynağa erişim izniniz yok</p>
                        </div>
                        
                        <div class="error-code">
                            <h3>404 - Kaynak Bulunamadı</h3>
                            <p>İstenen kaynak bulunamadı</p>
                        </div>
                        
                        <div class="error-code">
                            <h3>429 - Rate Limit Aşıldı</h3>
                            <p>İstek limiti aşıldı, daha sonra tekrar deneyin</p>
                        </div>
                        
                        <div class="error-code">
                            <h3>500 - Sunucu Hatası</h3>
                            <p>Sunucu tarafında beklenmeyen bir hata oluştu</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card full-width">
                <div class="card-header">
                    <h2>📬 İletişim</h2>
                </div>
                <div class="card-content">
                    <p>API ile ilgili sorularınız için <a href="mailto:api@kuresaletzinciri.com">api@kuresaletzinciri.com</a> adresine e-posta gönderin.</p>
                    <p>Daha fazla örnek ve detaylı bilgi için GitHub reposunu ziyaret edin: <a href="#" target="_blank">github.com/kuresaletzinciri/api</a></p>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.endpoint {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.endpoint:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.endpoint h3 {
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.endpoint h4 {
    color: #4a5568;
    margin: 1rem 0 0.5rem 0;
}

.endpoint p {
    color: #4a5568;
    margin-bottom: 1rem;
}

.endpoint ul {
    margin-bottom: 1rem;
}

.endpoint li {
    margin-bottom: 0.5rem;
    color: #4a5568;
}

pre {
    background: #f7fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    padding: 1rem;
    overflow-x: auto;
    margin: 1rem 0;
}

code {
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
}

.api-status {
    background: #f0fff4;
    border: 1px solid #c6f6d5;
    border-radius: 0.5rem;
    padding: 1rem;
    margin: 1rem 0;
}

.api-status h3 {
    margin-top: 0;
}

.api-status p {
    margin: 0.5rem 0;
}

.endpoint-info {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    padding: 1rem;
    margin: 1rem 0;
}

.error-codes {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.error-code {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    padding: 1rem;
}

.error-code h3 {
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.error-code p {
    color: #4a5568;
    margin: 0;
}
</style>

<?php include FRONTEND_DIR . '/components/footer.php'; ?>