<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Detayları - Küresel Etki Zinciri</title>
    <link rel="stylesheet" href="/Küresel/assets/css/style.css">
    <link rel="stylesheet" href="/Küresel/assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include FRONTEND_DIR . '/components/header.php'; ?>
    
    <main class="product-page">
        <div class="container">
            <div class="product-header">
                <div class="product-image">
                    <img src="/Küresel/assets/images/product-placeholder.jpg" alt="Soğuk Sıkım Nar Ekşisi">
                </div>
                <div class="product-info">
                    <h1>Soğuk Sıkım Nar Ekşisi</h1>
                    <p class="product-code">Ürün Kodu: PRD-001</p>
                    <p class="product-category">İçecek</p>
                    <div class="product-description">
                        Doğal olarak nar suyundan elde edilen bu ekşi, soğuk sıkım yöntemiyle hazırlanmış %100 doğal bir üründür. 
                        Herhangi bir katkı maddesi içermemektedir.
                    </div>
                </div>
            </div>
            
            <div class="product-tabs">
                <button class="product-tab-btn active" onclick="showProductTab('overview')">Genel Bilgiler</button>
                <button class="product-tab-btn" onclick="showProductTab('supply-chain')">Tedarik Zinciri</button>
                <button class="product-tab-btn" onclick="showProductTab('impact')">Etki Skorları</button>
                <button class="product-tab-btn" onclick="showProductTab('certifications')">Sertifikalar</button>
            </div>
            
            <div id="overview-tab" class="product-tab-content active">
                <div class="overview-grid">
                    <div class="overview-item">
                        <strong>Marka:</strong> Narlı Bahçe
                    </div>
                    <div class="overview-item">
                        <strong>Menşei:</strong> Türkiye
                    </div>
                    <div class="overview-item">
                        <strong>Ağırlık:</strong> 0.5 kg
                    </div>
                    <div class="overview-item">
                        <strong>Hacim:</strong> 0.5 L
                    </div>
                    <div class="overview-item">
                        <strong>Alt Kategori:</strong> Meyve Suyu
                    </div>
                    <div class="overview-item">
                        <strong>Paketleme Türü:</strong> Cam Şişe
                    </div>
                    <div class="overview-item">
                        <strong>Raf Ömrü:</strong> 365 gün
                    </div>
                    <div class="overview-item">
                        <strong>Hasat Sezonu:</strong> Ekim - Şubat
                    </div>
                </div>
            </div>
            
            <div id="supply-chain-tab" class="product-tab-content">
                <div class="supply-chain-steps">
                    <div class="supply-chain-step">
                        <div class="step-header">
                            <h3>Nar Tarımı</h3>
                            <span class="step-type">Üretim</span>
                        </div>
                        <div class="step-details">
                            <p><strong>Şirket:</strong> Narlı Bahçe Tarım İşletmesi</p>
                            <p><strong>Açıklama:</strong> Organik tarım yöntemleriyle nar yetiştiriciliği</p>
                            <p><strong>Adres:</strong> Isparta, Türkiye</p>
                            <p><strong>Başlangıç:</strong> 01.10.2024</p>
                            <p><strong>Bitiş:</strong> 28.02.2025</p>
                        </div>
                    </div>
                    
                    <div class="supply-chain-step">
                        <div class="step-header">
                            <h3>Sıkım ve İşleme</h3>
                            <span class="step-type">İşleme</span>
                        </div>
                        <div class="step-details">
                            <p><strong>Şirket:</strong> Narlı Bahçe İşleme Tesisi</p>
                            <p><strong>Açıklama:</strong> Soğuk sıkım yöntemiyle nar suyu eldesi</p>
                            <p><strong>Adres:</strong> Isparta Organize Sanayi Bölgesi</p>
                            <p><strong>Başlangıç:</strong> 01.03.2025</p>
                            <p><strong>Bitiş:</strong> 15.03.2025</p>
                        </div>
                    </div>
                    
                    <div class="supply-chain-step">
                        <div class="step-header">
                            <h3>Paketleme</h3>
                            <span class="step-type">Paketleme</span>
                        </div>
                        <div class="step-details">
                            <p><strong>Şirket:</strong> Narlı Bahçe Paketleme Merkezi</p>
                            <p><strong>Açıklama:</strong> Cam şişelere dolum ve etiketleme</p>
                            <p><strong>Adres:</strong> Isparta Organize Sanayi Bölgesi</p>
                            <p><strong>Başlangıç:</strong> 16.03.2025</p>
                            <p><strong>Bitiş:</strong> 20.03.2025</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="impact-tab" class="product-tab-content">
                <div class="impact-scores-container">
                    <div class="overall-score">
                        <h3>Genel Skor</h3>
                        <div class="score-badge grade-A">
                            85 <span class="grade">A</span>
                        </div>
                    </div>
                    
                    <div class="scores-grid">
                        <div class="score-category">
                            <h4>Çevresel Etki</h4>
                            <div class="score-item">
                                <span>Genel:</span>
                                <span class="score-value">82</span>
                            </div>
                            <div class="score-item">
                                <span>Karbon Ayak İzi:</span>
                                <span class="score-value">78</span>
                            </div>
                            <div class="score-item">
                                <span>Su Kullanımı:</span>
                                <span class="score-value">85</span>
                            </div>
                        </div>
                        
                        <div class="score-category">
                            <h4>Sosyal Etki</h4>
                            <div class="score-item">
                                <span>Genel:</span>
                                <span class="score-value">88</span>
                            </div>
                            <div class="score-item">
                                <span>Adil Ücret:</span>
                                <span class="score-value">92</span>
                            </div>
                            <div class="score-item">
                                <span>Çalışma Koşulları:</span>
                                <span class="score-value">85</span>
                            </div>
                        </div>
                        
                        <div class="score-category">
                            <h4>Şeffaflık</h4>
                            <div class="score-item">
                                <span>Genel:</span>
                                <span class="score-value">90</span>
                            </div>
                            <div class="score-item">
                                <span>Veri Tamamlığı:</span>
                                <span class="score-value">95</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="metrics-section">
                        <h4>Çevresel Metrikler</h4>
                        <div class="metrics-grid">
                            <div class="metric-item">
                                <span>Toplam Karbon Ayak İzi:</span>
                                <span>2.5 kg CO2</span>
                            </div>
                            <div class="metric-item">
                                <span>Toplam Su Kullanımı:</span>
                                <span>15 L</span>
                            </div>
                            <div class="metric-item">
                                <span>Toplam Enerji Tüketimi:</span>
                                <span>0.8 kWh</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="certifications-tab" class="product-tab-content">
                <div class="certifications-container">
                    <div class="certificate-item">
                        <h4>Organik Tarım Sertifikası</h4>
                        <p><strong>Veren Kurum:</strong> Türk Tarım ve Orman Bakanlığı</p>
                        <p><strong>Sertifika Numarası:</strong> TR-ORG-001</p>
                        <p><strong>Geçerlilik Tarihi:</strong> 31.12.2025</p>
                        <p><strong>Açıklama:</strong> Ürünün organik tarım yöntemleriyle üretilmiş olması</p>
                    </div>
                    
                    <div class="certificate-item">
                        <h4>Gıda Güvenliği Sertifikası</h4>
                        <p><strong>Veren Kurum:</strong> Türk Standartları Enstitüsü</p>
                        <p><strong>Sertifika Numarası:</strong> TSE-002</p>
                        <p><strong>Geçerlilik Tarihi:</strong> 31.12.2025</p>
                        <p><strong>Açıklama:</strong> Ürünün tüm gıda güvenliği standartlarını karşıladığını belgeler</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include FRONTEND_DIR . '/components/footer.php'; ?>
    
    <script>
    function showProductTab(tabName) {
        // Remove active class from all tabs
        document.querySelectorAll('.product-tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.product-tab-content').forEach(content => content.classList.remove('active'));
        
        // Add active class to selected tab
        document.querySelector(`button[onclick="showProductTab('${tabName}')"]`).classList.add('active');
        document.getElementById(`${tabName}-tab`).classList.add('active');
    }
    </script>
</body>
</html>