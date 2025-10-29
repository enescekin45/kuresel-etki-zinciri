<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Küresel Etki Zinciri - Global Impact Chain</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Fallback for font loading issues -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }
    </style>
</head>
<body>
    <?php include FRONTEND_DIR . '/components/header.php'; ?>
    
    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1>Şeffaflık ile Geleceği Şekillendirin</h1>
                    <p class="hero-subtitle">
                        Her ürünün hikayesini, çevresel ve sosyal maliyetlerini 
                        değiştirilemez ve doğrulanabilir şekilde son tüketiciye ulaştırın.
                    </p>
                    <div class="hero-actions">
                        <a href="/Küresel/?page=product" class="btn btn-primary">QR Kod Tarayın</a>
                        <a href="/Küresel/?page=company" class="btn btn-outline">Şirket Kaydı</a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="assets/images/supply-chain-illustration.svg" alt="Supply Chain Transparency" />
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features">
            <div class="container">
                <h2 class="section-title">Platform Özellikleri</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">🔗</div>
                        <h3>Blockchain Güvenliği</h3>
                        <p>Tüm veriler değiştirilemez blockchain teknolojisi ile güvence altında</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🌱</div>
                        <h3>Çevre Etkisi</h3>
                        <p>Karbon ayak izi, su kullanımı ve atık üretimi gibi çevresel metrikleri izleyin</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">👥</div>
                        <h3>Sosyal Etki</h3>
                        <p>Adil ücret, çalışma koşulları ve toplumsal etkiyi ölçün ve raporlayın</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📱</div>
                        <h3>QR Kod Tarama</h3>
                        <p>Tüketiciler ürün bilgilerini anında öğrenebilir</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🔍</div>
                        <h3>Bağımsız Doğrulama</h3>
                        <p>Uzman denetçiler verilerinizi doğrular ve güvenilirlik sağlar</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <h3>Etki Skorları</h3>
                        <p>Kapsamlı puanlama sistemi ile ürünlerinizin etkisini analiz edin</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="how-it-works">
            <div class="container">
                <h2 class="section-title">Nasıl Çalışır?</h2>
                <div class="steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <h3>Veri Girişi</h3>
                        <p>Tedarik zinciri katılımcıları üretim, işleme ve taşıma verilerini sisteme girer</p>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <h3>Doğrulama</h3>
                        <p>Bağımsız denetçiler verileri kontrol eder ve onaylar</p>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <h3>Blockchain Kayıt</h3>
                        <p>Doğrulanmış veriler değiştirilemez şekilde blockchain'e kaydedilir</p>
                    </div>
                    <div class="step">
                        <div class="step-number">4</div>
                        <h3>QR Kod Üretimi</h3>
                        <p>Her ürün için benzersiz QR kod oluşturulur</p>
                    </div>
                    <div class="step">
                        <div class="step-number">5</div>
                        <h3>Tüketici Erişimi</h3>
                        <p>Tüketiciler QR kodu tarayarak ürün hikayesine ulaşır</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta">
            <div class="container">
                <div class="cta-content">
                    <h2>Şeffaflık Devriminin Parçası Olun</h2>
                    <p>Sürdürülebilir ve etik bir tedarik zinciri için bugün başlayın</p>
                    <div class="cta-actions">
                        <a href="/Küresel/?page=company" class="btn btn-primary">Şirket Olarak Katılın</a>
                        <a href="/Küresel/?page=validator" class="btn btn-outline">Doğrulayıcı Olun</a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include FRONTEND_DIR . '/components/footer.php'; ?>
    
    <script src="assets/js/main.js"></script>
</body>
</html>