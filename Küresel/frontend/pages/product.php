<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Bilgilerini Öğrenin - Küresel Etki Zinciri</title>
    <link rel="stylesheet" href="/Küresel/assets/css/style.css">
    <link rel="stylesheet" href="/Küresel/assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include FRONTEND_DIR . '/components/header.php'; ?>
    
    <main class="product-page">
        <div class="container">
            <?php if (isset($_GET['id']) || isset($_GET['uuid'])): ?>
                <!-- Product Details View -->
                <div id="product-details">
                    <div class="loading-spinner" id="loading">
                        <div class="spinner"></div>
                        <p>Ürün bilgileri yükleniyor...</p>
                    </div>
                    
                    <div id="product-content" class="product-content" style="display: none;">
                        <!-- Content will be loaded dynamically -->
                    </div>
                    
                    <div id="error-message" class="error-message" style="display: none;">
                        <div class="error-icon">⚠️</div>
                        <h3>Ürün Bulunamadı</h3>
                        <p>Belirtilen ürün bulunamadı veya erişim izni yok.</p>
                        <a href="/Küresel/?page=product" class="btn btn-primary">Yeni Arama Yap</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- QR Scanner / Search View -->
                <div class="search-section">
                    <div class="search-header">
                        <h1>Ürün Bilgilerini Öğrenin</h1>
                        <p>QR kod tarayarak veya ürün kodunu girerek ürününüzün şeffaflık bilgilerine ulaşın</p>
                    </div>
                    
                    <div class="search-tabs">
                        <button class="tab-button active" onclick="switchTab('qr')">QR Kod Tarama</button>
                        <button class="tab-button" onclick="switchTab('manual')">Manuel Arama</button>
                    </div>
                    
                    <div id="qr-tab" class="tab-content active">
                        <div class="qr-scanner-container">
                            <div id="qr-reader" class="qr-reader"></div>
                            <div class="qr-scanner-controls">
                                <button id="start-scan" class="btn btn-primary">Kamera Başlat</button>
                                <button id="stop-scan" class="btn btn-outline" style="display: none;">Taramayı Durdur</button>
                            </div>
                            <div class="qr-scanner-info">
                                <p>📱 QR kodu kameranızın görüş alanına getirin</p>
                                <p>🔒 Kamera erişimi yalnızca tarama için kullanılır</p>
                            </div>
                        </div>
                    </div>
                    
                    <div id="manual-tab" class="tab-content">
                        <div class="manual-search-container">
                            <form id="manual-search-form" class="search-form">
                                <div class="form-group">
                                    <label for="product-code">Ürün Kodu</label>
                                    <input type="text" id="product-code" name="product_code" 
                                           placeholder="Örn: COMP123-PROD456789" 
                                           class="form-control">
                                </div>
                                
                                <div class="form-group">
                                    <label for="barcode">Barkod</label>
                                    <input type="text" id="barcode" name="barcode" 
                                           placeholder="Ürün barkodunu girin" 
                                           class="form-control">
                                </div>
                                
                                <div class="form-group">
                                    <label for="search-text">Genel Arama</label>
                                    <input type="text" id="search-text" name="search" 
                                           placeholder="Ürün adı, şirket adı veya kategori" 
                                           class="form-control">
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-large">
                                    🔍 Ürün Ara
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="features-preview">
                    <h2>Ne Öğreneceksiniz?</h2>
                    <div class="features-grid">
                        <div class="feature-item">
                            <div class="feature-icon">🌱</div>
                            <h3>Çevresel Etki</h3>
                            <p>Karbon ayak izi, su kullanımı, enerji tüketimi</p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">👥</div>
                            <h3>Sosyal Sorumluluk</h3>
                            <p>Çalışma koşulları, adil ücret, toplumsal etki</p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🔗</div>
                            <h3>Tedarik Zinciri</h3>
                            <p>Üretimden teslimata kadar tüm süreç</p>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">✅</div>
                            <h3>Doğrulanmış Veriler</h3>
                            <p>Bağımsız denetçiler tarafından onaylanmış</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include FRONTEND_DIR . '/components/footer.php'; ?>
    
    <!-- Include QR Code Scanner Library -->
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="/Küresel/assets/js/main.js?v=<?php echo time(); ?>"></script>
    <script src="/Küresel/assets/js/qr-scanner.js?v=<?php echo time(); ?>"></script>
    <script src="/Küresel/assets/js/product.js?v=<?php echo time(); ?>"></script>
    
    <script>
        // Initialize page based on URL parameters
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const productId = urlParams.get('id');
            const productUuid = urlParams.get('uuid');
            
            // Log for debugging
            console.log('Product page loaded with ID:', productId, 'UUID:', productUuid);
            console.log('Full URL:', window.location.href);
            console.log('Query string:', window.location.search);
            
            // Log all parameters
            for (const [key, value] of urlParams.entries()) {
                console.log('Parameter:', key, '=', value);
            }
            
            if (productId || productUuid) {
                // Use the external loadProductDetails function
                loadProductDetails(productId, productUuid);
            }
        });
        
        function switchTab(tabName) {
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            // Add active class to selected tab
            document.querySelector(`[onclick="switchTab('${tabName}')"]`).classList.add('active');
            document.getElementById(`${tabName}-tab`).classList.add('active');
        }
        
        // Add form submission handler for manual search
        document.getElementById('manual-search-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const productCode = document.getElementById('product-code').value.trim();
            const barcode = document.getElementById('barcode').value.trim();
            const searchText = document.getElementById('search-text').value.trim();
            
            // Determine which field to use for search
            let searchValue = '';
            if (productCode) {
                searchValue = productCode;
            } else if (barcode) {
                searchValue = barcode;
            } else if (searchText) {
                searchValue = searchText;
            }
            
            if (!searchValue) {
                showError('Lütfen aramak için bir değer girin');
                return;
            }
            
            // Load product details using the search value
            loadProductDetails(searchValue);
        });

    </script>
</body>
</html>