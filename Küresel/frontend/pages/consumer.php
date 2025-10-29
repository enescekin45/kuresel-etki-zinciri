<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tüketici Paneli - Küresel Etki Zinciri</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .consumer-dashboard {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .welcome-banner {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .welcome-banner h1 {
            color: #4a5568;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }
        
        .welcome-banner p {
            color: #718096;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-hero-outline {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .btn-hero-outline:hover {
            background: rgba(102, 126, 234, 0.1);
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .card-header h2 {
            color: #2d3748;
            font-size: 1.5rem;
            margin: 0;
        }
        
        .section-actions a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .section-actions a:hover {
            text-decoration: underline;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 0.75rem;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .stat-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .stat-content h3 {
            color: #4a5568;
            margin: 0 0 0.5rem 0;
            font-size: 1rem;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
            margin: 0;
        }
        
        .stat-content small {
            color: #a0aec0;
            font-size: 0.8rem;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .product-card {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .product-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #48bb78;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .product-image {
            height: 150px;
            background: #f7fafc;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .product-image img {
            max-width: 80%;
            max-height: 80%;
        }
        
        .product-info {
            padding: 1rem;
        }
        
        .product-info h3 {
            margin: 0 0 0.5rem 0;
            color: #2d3748;
            font-size: 1.1rem;
        }
        
        .product-company {
            color: #718096;
            font-size: 0.9rem;
            margin: 0 0 1rem 0;
        }
        
        .product-scores {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .score {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 500;
        }
        
        .score.environmental {
            background: rgba(72, 187, 120, 0.1);
            color: #48bb78;
        }
        
        .score.social {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        
        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #cbd5e0;
        }
        
        .empty-state h3 {
            color: #4a5568;
            margin-bottom: 0.5rem;
        }
        
        .empty-state p {
            color: #718096;
            margin-bottom: 1.5rem;
        }
        
        .sidebar-widgets {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .sidebar-widget {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .sidebar-widget h3 {
            color: #2d3748;
            margin-top: 0;
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
        }
        
        .guide-steps {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .guide-step {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .step-number {
            background: #667eea;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        
        .guide-step p {
            margin: 0;
            color: #4a5568;
        }
        
        .insight-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .insight-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        
        .insight-text strong {
            display: block;
            color: #2d3748;
            margin-bottom: 0.25rem;
        }
        
        .insight-text p {
            margin: 0;
            color: #718096;
            font-size: 0.9rem;
        }
        
        .tips-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .tips-list li {
            padding: 0.5rem 0;
            padding-left: 1.5rem;
            position: relative;
            color: #4a5568;
        }
        
        .tips-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #48bb78;
            font-weight: bold;
        }
        
        /* Footer styles */
        .footer {
            background: #2d3748;
            color: white;
            padding: 3rem 0 1rem;
            margin-top: 3rem;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .footer-section h3 {
            margin-bottom: 1rem;
            color: white;
            font-size: 1.2rem;
        }
        
        .footer-section h4 {
            margin-bottom: 1rem;
            color: white;
            font-size: 1rem;
        }
        
        .footer-section ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-section ul li {
            margin-bottom: 0.5rem;
        }
        
        .footer-section a {
            color: #cbd5e0;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .footer-section a:hover {
            color: white;
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .newsletter-form {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .newsletter-form input {
            flex: 1;
            padding: 0.5rem;
            border: 1px solid #4a5568;
            border-radius: 0.25rem;
            background: transparent;
            color: white;
        }
        
        .newsletter-form input::placeholder {
            color: #a0aec0;
        }
        
        .footer-bottom {
            border-top: 1px solid #4a5568;
            padding-top: 1rem;
        }
        
        .footer-bottom-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .footer-links {
            display: flex;
            gap: 1rem;
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 0 0.5rem;
            }
            
            .welcome-banner {
                padding: 1.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn-hero {
                width: 100%;
                text-align: center;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php 
    // Check if user is logged in
    $auth = Auth::getInstance();
    if (!$auth->isLoggedIn()) {
        header('Location: /Küresel/?page=login');
        exit;
    }
    
    $currentUser = $auth->getCurrentUser();
    
    // Check if user is a consumer
    if ($currentUser->getUserType() !== 'consumer') {
        header('Location: /Küresel/');
        exit;
    }
    
    include FRONTEND_DIR . '/components/header.php'; 
    ?>
    
    <main class="consumer-dashboard">
        <div class="dashboard-container">
            <!-- Welcome Section -->
            <div class="welcome-banner">
                <h1>👋 Hoş Geldiniz, <?= htmlspecialchars($currentUser->getFirstName()) ?>!</h1>
                <p>Ürünlerin hikayesini keşfedin ve bilinçli tüketim yapın. Sürdürülebilir bir gelecek için her tarama önemlidir.</p>
                <div class="action-buttons">
                    <a href="/Küresel/?page=product" class="btn-hero">
                        📱 QR Kod Tara
                    </a>
                    <a href="/Küresel/?page=profile" class="btn-hero btn-hero-outline">
                        👤 Profil
                    </a>
                    <a href="/Küresel/?page=settings" class="btn-hero btn-hero-outline">
                        ⚙️ Ayarlar
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">🔍</div>
                    <div class="stat-content">
                        <h3>Taranan Ürünler</h3>
                        <p class="stat-number">0</p>
                        <small>Son 30 gün</small>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">⭐</div>
                    <div class="stat-content">
                        <h3>Favori Markalar</h3>
                        <p class="stat-number">0</p>
                        <small>Kayıtlı marka</small>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">🌱</div>
                    <div class="stat-content">
                        <h3>Çevre Etkisi</h3>
                        <p class="stat-number">-</p>
                        <small>Tasarruf edilen CO₂</small>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">🏆</div>
                    <div class="stat-content">
                        <h3>Bilinçli Seçimler</h3>
                        <p class="stat-number">0</p>
                        <small>Sürdürülebilir ürün</small>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="dashboard-grid">
                <!-- Recent Scans -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>📱 Son Taranan Ürünler</h2>
                        <div class="section-actions">
                            <a href="/Küresel/?page=product">Yeni Tarama</a>
                        </div>
                    </div>
                    <div class="empty-state">
                        <div class="empty-icon">📦</div>
                        <h3>Henüz ürün taramadınız</h3>
                        <p>QR kod tarayarak ürünlerin hikayesini öğrenmeye başlayın</p>
                        <a href="/Küresel/?page=product" class="btn-hero">QR Kod Tara</a>
                    </div>
                </div>

                <!-- Featured Products -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>⭐ Öne Çıkan Ürünler</h2>
                        <div class="section-actions">
                            <a href="/Küresel/?page=products">Tümünü Gör</a>
                        </div>
                    </div>
                    <div class="products-grid">
                        <div class="product-card">
                            <div class="product-badge">A+</div>
                            <div class="product-image">
                                <img src="assets/images/placeholder-product.png" alt="Organik Zeytinyağı">
                            </div>
                            <div class="product-info">
                                <h3>Organik Zeytinyağı</h3>
                                <p class="product-company">Örnek Şirket A.Ş.</p>
                                <div class="product-scores">
                                    <span class="score environmental">🌱 95/100</span>
                                    <span class="score social">👥 92/100</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="product-card">
                            <div class="product-badge">A</div>
                            <div class="product-image">
                                <img src="assets/images/placeholder-product.png" alt="Organik Pamuklu Tişört">
                            </div>
                            <div class="product-info">
                                <h3>Organik Pamuklu Tişört</h3>
                                <p class="product-company">Tekstil A.Ş.</p>
                                <div class="product-scores">
                                    <span class="score environmental">🌱 88/100</span>
                                    <span class="score social">👥 90/100</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Guide -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>📖 Hızlı Rehber</h2>
                    </div>
                    <div class="guide-steps">
                        <div class="guide-step">
                            <div class="step-number">1</div>
                            <p>Ürün üzerindeki QR kodu bulun</p>
                        </div>
                        <div class="guide-step">
                            <div class="step-number">2</div>
                            <p>QR Kod Tara butonuna tıklayın</p>
                        </div>
                        <div class="guide-step">
                            <div class="step-number">3</div>
                            <p>Kameranızı QR koda tutun</p>
                        </div>
                        <div class="guide-step">
                            <div class="step-number">4</div>
                            <p>Ürün bilgilerini inceleyin</p>
                        </div>
                    </div>
                    <a href="/Küresel/?page=product" class="btn-hero btn-hero-outline">QR Kod Tara</a>
                </div>

                <!-- Impact Insights -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>🌍 Etki Bilgileri</h2>
                    </div>
                    <div class="insight-item">
                        <div class="insight-icon">🌱</div>
                        <div class="insight-text">
                            <strong>Çevresel Etki</strong>
                            <p>Ürünlerin karbon ayak izini öğrenin</p>
                        </div>
                    </div>
                    <div class="insight-item">
                        <div class="insight-icon">👥</div>
                        <div class="insight-text">
                            <strong>Sosyal Sorumluluk</strong>
                            <p>Adil ticaret ve işçi haklarını kontrol edin</p>
                        </div>
                    </div>
                    <div class="insight-item">
                        <div class="insight-icon">🔍</div>
                        <div class="insight-text">
                            <strong>Şeffaflık</strong>
                            <p>Tedarik zincirini takip edin</p>
                        </div>
                    </div>
                </div>

                <!-- Tips -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>💡 İpuçları</h2>
                    </div>
                    <ul class="tips-list">
                        <li>A+ ve A notlu ürünleri tercih edin</li>
                        <li>Yerel üreticileri destekleyin</li>
                        <li>Organik sertifikalı ürünlere bakın</li>
                        <li>Adil ticaret etiketli ürünleri seçin</li>
                        <li>Paketlemesi az olan ürünleri tercih edin</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <!-- Detailed Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Platform</h3>
                    <ul>
                        <li><a href="/Küresel/?page=product">Ürün Tarama</a></li>
                        <li><a href="/Küresel/?page=register&user_type=company">Şirket Kaydı</a></li>
                        <li><a href="/Küresel/?page=register&user_type=validator">Doğrulayıcı Ol</a></li>
                        <li><a href="/Küresel/?page=docs">API Dokümantasyonu</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Şirket</h3>
                    <ul>
                        <li><a href="/Küresel/?page=about">Hakkımızda</a></li>
                        <li><a href="/Küresel/?page=team">Ekibimiz</a></li>
                        <li><a href="/Küresel/?page=careers">Kariyer</a></li>
                        <li><a href="/Küresel/?page=press">Basın</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Destek</h3>
                    <ul>
                        <li><a href="/Küresel/?page=help">Yardım Merkezi</a></li>
                        <li><a href="/Küresel/?page=contact">İletişim</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Yasal</h3>
                    <ul>
                        <li><a href="/Küresel/?page=privacy">Gizlilik Politikası</a></li>
                        <li><a href="/Küresel/?page=terms">Kullanım Şartları</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Bülten</h3>
                    <p>Sürdürülebilirlik haberlerinden haberdar olun.</p>
                    <form class="newsletter-form">
                        <input type="email" placeholder="E-posta adresiniz" required>
                        <button type="submit" class="btn btn-primary">Kayıt Ol</button>
                    </form>
                    <div class="social-links">
                        <a href="#" aria-label="Twitter">🐦</a>
                        <a href="#" aria-label="Facebook">📘</a>
                        <a href="#" aria-label="Instagram">📷</a>
                        <a href="#" aria-label="LinkedIn">👔</a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p>&copy; 2025 Küresel Etki Zinciri. Tüm hakları saklıdır.</p>
                    <div class="footer-links">
                        <a href="/Küresel/?page=privacy">Gizlilik</a>
                        <a href="/Küresel/?page=terms">Şartlar</a>
                        <a href="/Küresel/?page=cookies">Çerezler</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="assets/js/main.js"></script>
</body>
</html>