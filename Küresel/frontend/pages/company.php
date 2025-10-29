<?php
// Define required constants only if not already defined
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', __DIR__ . '/../..');
}

if (!defined('CONFIG_DIR')) {
    define('CONFIG_DIR', ROOT_DIR . '/config');
}

if (!defined('CLASSES_DIR')) {
    define('CLASSES_DIR', ROOT_DIR . '/classes');
}

if (!defined('FRONTEND_DIR')) {
    define('FRONTEND_DIR', ROOT_DIR . '/frontend');
}

require_once CLASSES_DIR . '/Auth.php';
require_once CLASSES_DIR . '/Company.php';
require_once CLASSES_DIR . '/Product.php';

$auth = Auth::getInstance();

// Redirect to login if not authenticated
if (!$auth->isLoggedIn()) {
    header('Location: /Küresel/?page=login');
    exit;
}

$currentUser = $auth->getCurrentUser();

// Only allow company users to access this page
if (!$currentUser->isCompany()) {
    header('Location: /Küresel/');
    exit;
}

$company = new Company();
$companyData = null;
try {
    $company->loadByUserId($currentUser->getId());
    $companyData = $company->toArray();
} catch (Exception $e) {
    // Company not found - user might need to complete company registration
    $companyData = null;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şirket Paneli - Küresel Etki Zinciri</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="<?= $auth->generateCSRFToken() ?>">
</head>
<body>
    <?php include FRONTEND_DIR . '/components/header.php'; ?>
    
    <main class="dashboard">
        <div class="container">
            <div class="dashboard-header">
                <h1>Şirket Paneli</h1>
                <div class="dashboard-actions">
                    <a href="/Küresel/?page=product&action=add" class="btn btn-primary">Yeni Ürün Ekle</a>
                </div>
            </div>

            <div class="dashboard-grid">
                <!-- Company Overview -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>Şirket Bilgileri</h2>
                        <button class="btn btn-outline btn-sm" onclick="editCompanyInfo()">Düzenle</button>
                    </div>
                    <div class="card-content">
                        <?php if ($companyData): ?>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Şirket Adı:</label>
                                    <span><?= htmlspecialchars($companyData['company_name'] ?? 'Belirtilmemiş') ?></span>
                                </div>
                                <div class="info-item">
                                    <label>Sektör:</label>
                                    <span><?= htmlspecialchars($companyData['industry_sector'] ?? 'Belirtilmemiş') ?></span>
                                </div>
                                <div class="info-item">
                                    <label>Adres:</label>
                                    <span>
                                        <?php 
                                        $addressParts = [];
                                        if (!empty($companyData['address_line1'])) $addressParts[] = $companyData['address_line1'];
                                        if (!empty($companyData['address_line2'])) $addressParts[] = $companyData['address_line2'];
                                        if (!empty($companyData['postal_code'])) $addressParts[] = $companyData['postal_code'];
                                        if (!empty($companyData['city'])) $addressParts[] = $companyData['city'];
                                        if (!empty($companyData['state'])) $addressParts[] = $companyData['state'];
                                        if (!empty($companyData['country'])) $addressParts[] = $companyData['country'];
                                        
                                        echo !empty($addressParts) ? htmlspecialchars(implode(', ', $addressParts)) : 'Belirtilmemiş';
                                        ?>
                                    </span>
                                </div>
                                <div class="info-item">
                                    <label>Kayıt Tarihi:</label>
                                    <span>
                                        <?php 
                                        if (!empty($companyData['created_at'])) {
                                            echo date('d.m.Y', strtotime($companyData['created_at']));
                                        } else {
                                            echo 'Belirtilmemiş';
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>İstatistikler</h2>
                    </div>
                    <div class="card-content">
                        <div class="chart-container">
                            <div class="chart-wrapper">
                                <div id="chart-loading" style="display: flex; justify-content: center; align-items: center; height: 100%;">
                                    <div class="loading">İstatistikler yükleniyor...</div>
                                </div>
                                <canvas id="statistics-chart" style="display: none;"></canvas>
                                <div id="chart-fallback" style="display: none; text-align: center; padding: 2rem; color: #718096;">
                                    <p>İstatistikler yüklenemedi. Lütfen sayfayı yenileyin.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Products -->
                <div class="dashboard-card full-width">
                    <div class="recent-products-header">
                        <h2>Son Eklenen Ürünler</h2>
                        <a href="/Küresel/?page=product&action=list" class="view-all-link">Tümünü Gör →</a>
                    </div>
                    <div class="card-content">
                        <div id="recent-products-container">
                            <div class="loading">Veriler yükleniyor...</div>
                        </div>
                    </div>
                </div>

                <!-- Environmental Impact Summary -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>Çevresel Etki Özeti</h2>
                    </div>
                    <div class="card-content">
                        <div class="impact-summary" data-load="/company/environmental-impact" data-template="impact-summary">
                            <div class="loading">Veriler yükleniyor...</div>
                        </div>
                    </div>
                </div>

                <!-- Social Impact Summary -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>Sosyal Etki Özeti</h2>
                    </div>
                    <div class="card-content">
                        <div class="social-impact" data-load="/company/social-impact" data-template="social-impact-summary">
                            <div class="loading">Veriler yükleniyor...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include FRONTEND_DIR . '/components/footer.php'; ?>
    
    <script src="assets/js/main.js"></script>
    <script src="assets/js/company-dashboard.js"></script>
    <script src="assets/js/libs/chart.umd.min.js"></script>

<script>
// Enhanced script for recent products display
document.addEventListener('DOMContentLoaded', function() {
    // Load recent products with enhanced display
    loadRecentProducts();
});

async function loadRecentProducts() {
    try {
        const response = await fetch('/Küresel/api/v1/company/products/recent');
        const result = await response.json();
        
        const container = document.getElementById('recent-products-container');
        
        if (result.success && result.data && result.data.length > 0) {
            let html = '';
            result.data.forEach(product => {
                // Handle product image
                let imageHtml = '<span style="font-size: 24px;">📦</span>';
                if (product.product_images && product.product_images.length > 0) {
                    const image = product.product_images[0];
                    if (typeof image === 'object' && image.url) {
                        // Handle object with url property
                        imageHtml = `<img src="${escapeHtml(image.url)}" alt="${escapeHtml(product.product_name || 'Ürün')}" onerror="this.parentElement.innerHTML='<span style=\\'font-size: 24px;\\'>📦</span>'">`;
                    } else if (typeof image === 'string') {
                        // Handle string path
                        let imagePath = image;
                        if (imagePath.startsWith('/')) {
                            imagePath = '/Küresel' + imagePath;
                        } else if (!imagePath.startsWith('http')) {
                            imagePath = '/Küresel/' + imagePath;
                        }
                        imageHtml = `<img src="${escapeHtml(imagePath)}" alt="${escapeHtml(product.product_name || 'Ürün')}" onerror="this.parentElement.innerHTML='<span style=\\'font-size: 24px;\\'>📦</span>'">`;
                    } else {
                        // Handle other cases
                        imageHtml = '<span style="font-size: 24px;">📦</span>';
                    }
                }
                
                html += `
                <div class="product-preview-card">
                    <div class="product-preview-image">
                        ${imageHtml}
                    </div>
                    <div class="product-preview-info">
                        <div class="product-preview-name" title="${escapeHtml(product.product_name || 'Belirtilmemiş')}">${escapeHtml(product.product_name || 'Belirtilmemiş')}</div>
                        <div class="product-preview-category">${escapeHtml(product.category || 'Belirtilmemiş')}</div>
                        <div class="product-preview-date">${formatDate(product.created_at || new Date())}</div>
                    </div>
                    <div class="product-actions">
                        <a href="/Küresel/?page=product&action=edit&id=${product.id}" class="btn btn-sm btn-warning" title="Düzenle">✏️</a>
                        <a href="/Küresel/?page=product&action=view&id=${product.id}" class="btn btn-sm btn-primary" title="İncele">👁️</a>
                    </div>
                </div>
                `;
            });
            container.innerHTML = html;
        } else {
            // Remove the "İlk Ürünü Ekle" button as requested by the user
            container.innerHTML = '<div class="empty-state"><p>Ürün bulunamadı</p></div>';
        }
    } catch (error) {
        console.error('Error loading recent products:', error);
        // Improved error message without red color
        document.getElementById('recent-products-container').innerHTML = '<div class="error-message" style="padding: 1rem; text-align: center; color: #4a5568; background: #f7fafc; border-radius: 0.5rem;">Veriler yüklenirken bir hata oluştu. Lütfen sayfayı yenileyin.</div>';
    }
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.toString().replace(/[&<>"']/g, m => map[m]);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('tr-TR');
}

function editCompanyInfo() {
    showEditCompanyModal();
}

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Load dashboard data
    loadDashboardData();
});

// Wait for data to load before initializing charts
function waitForDataAndInitCharts() {
    const maxAttempts = 20;
    let attempts = 0;
    
    const checkData = () => {
        attempts++;
        // Fixed the selector to match the actual data-load attributes
        const productElement = document.querySelector('[data-load="/company/stats/products"]');
        const scanElement = document.querySelector('[data-load="/company/stats/scans"]');
        const validationElement = document.querySelector('[data-load="/company/stats/validation"]');
        const pendingElement = document.querySelector('[data-load="/company/stats/pending"]');
        
        // Check if all data elements exist and have numeric content
        if (productElement && scanElement && validationElement && pendingElement &&
            !isNaN(parseInt(productElement.textContent)) && 
            !isNaN(parseInt(scanElement.textContent)) && 
            !isNaN(parseInt(validationElement.textContent)) && 
            !isNaN(parseInt(pendingElement.textContent))) {
            initCharts();
        } else if (attempts < maxAttempts) {
            setTimeout(checkData, 500);
        } else {
            // Fallback: initialize charts with API calls directly
            initCharts();
        }
    };
    
    checkData();
}

// Initialize charts
async function initCharts() {
    try {
        // Show loading indicator
        const loadingElement = document.getElementById('chart-loading');
        const statsCtx = document.getElementById('statistics-chart');
        const fallbackElement = document.getElementById('chart-fallback');
        
        if (loadingElement) {
            loadingElement.style.display = 'flex';
        }
        if (statsCtx) {
            statsCtx.style.display = 'none';
        }
        if (fallbackElement) {
            fallbackElement.style.display = 'none';
        }
        
        // Get statistics data from the API endpoints directly
        const [productsResponse, scansResponse, validationResponse, pendingResponse] = await Promise.all([
            fetch('/Küresel/api/v1/company/stats/products'),
            fetch('/Küresel/api/v1/company/stats/scans'),
            fetch('/Küresel/api/v1/company/stats/validation'),
            fetch('/Küresel/api/v1/company/stats/pending')
        ]);
        
        const productsData = await productsResponse.json();
        const scansData = await scansResponse.json();
        const validationData = await validationResponse.json();
        const pendingData = await pendingResponse.json();
        
        const totalProducts = productsData.success ? productsData.data : 0;
        const qrScans = scansData.success ? scansData.data : 0;
        const validated = validationData.success ? validationData.data : 0;
        const pending = pendingData.success ? pendingData.data : 0;
        
        // Hide loading indicator
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }
        
        // Create statistics chart
        if (statsCtx && fallbackElement) {
            // Clear any existing chart
            if (Chart.getChart(statsCtx)) {
                Chart.getChart(statsCtx).destroy();
            }
            
            new Chart(statsCtx, {
                type: 'pie',
                data: {
                    labels: ['Toplam Ürün', 'QR Tarama', 'Doğrulanmış', 'Bekleyen'],
                    datasets: [{
                        data: [totalProducts, qrScans, validated, pending],
                        backgroundColor: [
                            '#3182ce',
                            '#38a169',
                            '#48bb78',
                            '#ed8936'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: true,
                            text: 'İstatistikler',
                            font: {
                                size: 16
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.raw}`;
                                }
                            }
                        }
                    }
                }
            });
            
            // Show chart
            statsCtx.style.display = 'block';
        }
    } catch (error) {
        console.error('Error initializing charts:', error);
        // Show fallback message with improved styling
        const loadingElement = document.getElementById('chart-loading');
        const statsCtx = document.getElementById('statistics-chart');
        const fallbackElement = document.getElementById('chart-fallback');
        
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }
        if (statsCtx) {
            statsCtx.style.display = 'none';
        }
        if (fallbackElement) {
            fallbackElement.style.display = 'block';
        }
    }
}

// Call the function to initialize charts
document.addEventListener('DOMContentLoaded', function() {
    waitForDataAndInitCharts();
});
</script>
</body>
</html>