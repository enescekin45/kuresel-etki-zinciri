// Product search functionality
class ProductSearch {
    constructor() {
        this.init();
    }
    
    init() {
        this.bindEvents();
    }
    
    bindEvents() {
        // Manual search form
        const manualSearchForm = document.getElementById('manual-search-form');
        if (manualSearchForm) {
            manualSearchForm.addEventListener('submit', (e) => this.handleManualSearch(e));
        }
        
        // Search input validation
        const productCodeInput = document.getElementById('product-code');
        if (productCodeInput) {
            productCodeInput.addEventListener('input', (e) => {
                // Allow only uppercase letters, numbers, and dash
                e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
            });
        }
        
        const barcodeInput = document.getElementById('barcode');
        if (barcodeInput) {
            barcodeInput.addEventListener('input', (e) => {
                // Allow only numbers
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            });
        }
    }
    
    async handleManualSearch(event) {
        event.preventDefault();
        
        // Prevent multiple submissions
        const submitBtn = event.target.querySelector('button[type="submit"]');
        if (submitBtn && submitBtn.disabled) {
            console.log('Form submission already in progress');
            return;
        }
        
        const formData = new FormData(event.target);
        const productCode = formData.get('product_code');
        const barcode = formData.get('barcode');
        const search = formData.get('search');
        
        // Check which field has a value
        let searchType, searchValue;
        if (productCode && productCode.trim() !== '') {
            searchType = 'product_code';
            searchValue = productCode.trim();
        } else if (barcode && barcode.trim() !== '') {
            searchType = 'barcode';
            searchValue = barcode.trim();
        } else if (search && search.trim() !== '') {
            searchType = 'general';
            searchValue = search.trim();
        } else {
            // If all fields are empty, search for all products
            searchType = 'all';
            searchValue = '';
        }
        
        try {
            const submitBtn = event.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Aranıyor...';
            
            // Store original text as data attribute for later use
            submitBtn.setAttribute('data-original-text', originalText);
            
            // Build query parameters based on search type
            let queryParams = {};
            switch(searchType) {
                case 'product_code':
                    queryParams.product_code = searchValue;
                    break;
                case 'barcode':
                    queryParams.barcode = searchValue;
                    break;
                case 'general':
                    queryParams.search = searchValue;
                    break;
                // For 'all' type, we don't add any parameters
            }
            
            // Determine the correct API base URL
            let apiUrl;
            if (window.location.pathname.startsWith('/Küresel/')) {
                apiUrl = '/Küresel/api/v1/products/get';
            } else {
                // When running with PHP development server at root
                apiUrl = '/Küresel/api/v1/products/get';
            }
            
            const queryString = new URLSearchParams(queryParams).toString();
            
            console.log('Making API request to:', apiUrl + (queryString ? '?' + queryString : ''));
            
            const response = await fetch(apiUrl + (queryString ? '?' + queryString : ''));
            
            // Check if response is OK
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Get response text first to check what we're getting
            const responseText = await response.text();
            
            console.log('Raw response text:', responseText);
            
            // Check if response is empty
            if (!responseText || responseText.trim() === '') {
                throw new Error('Sunucudan boş yanıt alındı. Lütfen daha sonra tekrar deneyin.');
            }
            
            // Check if response is HTML (error page) instead of JSON
            if (responseText.trim().startsWith('<')) {
                throw new Error('Sunucudan geçersiz yanıt alındı. API erişiminde sorun oluştu.');
            }
            
            // Try to parse JSON
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (jsonError) {
                console.error('Invalid JSON response:', responseText);
                throw new Error('Sunucudan geçersiz yanıt alındı. Lütfen daha sonra tekrar deneyin.');
            }
            
            if (result.success) {
                if (result.data.products && result.data.products.length > 0) {
                    this.displaySearchResults(result.data.products);
                } else if (result.data) {
                    // Single product result
                    window.location.href = `/Küresel/?page=product&uuid=${result.data.uuid}`;
                } else {
                    // Show a more informative message when no products are found
                    const message = result.message || 'Arama kriterlerinize uygun ürün bulunamadı';
                    alert(message + '\n\nLütfen farklı bir arama terimi deneyin veya tüm ürünleri görmek için formu boş bırakın.');
                }
            } else {
                const message = result.message || 'Arama sırasında hata oluştu';
                alert(message);
            }
            
            submitBtn.disabled = false;
            submitBtn.textContent = submitBtn.getAttribute('data-original-text') || '🔍 Ürün Ara';
            
        } catch (error) {
            console.error('Search error:', error);
            const errorMessage = error.message || 'Arama sırasında hata oluştu';
            alert('Arama sırasında hata oluştu: ' + errorMessage);
            
            const submitBtn = event.target.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = submitBtn.getAttribute('data-original-text') || '🔍 Ürün Ara';
            }
        }
    }
    
    displaySearchResults(products) {
        if (products.length === 1) {
            // Single product - redirect directly
            const product = products[0];
            window.location.href = `/Küresel/?page=product&uuid=${product.uuid}`;
            return;
        }
        
        // Multiple products - show selection modal
        this.showProductSelectionModal(products);
    }
    
    showProductSelectionModal(products) {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Arama Sonuçları (${products.length} ürün)</h3>
                    <button class="modal-close">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="search-results">
                        ${products.map(product => {
                            // FIXED: Ensure proper image path handling for search results
                            let productImage = '/Küresel/assets/images/placeholder.jpg';
                            if (product.product_images && product.product_images.length > 0) {
                                let imagePath = product.product_images[0];
                                if (imagePath.startsWith('/')) {
                                    productImage = '/Küresel' + imagePath;
                                } else {
                                    productImage = '/Küresel/' + imagePath;
                                }
                            }
                            return `
                                <div class="product-result" data-uuid="${product.uuid}">
                                    <div class="product-image">
                                        <img src="${productImage}" 
                                             alt="${product.product_name}" onerror="this.src='/Küresel/assets/images/placeholder.jpg'">
                                    </div>
                                    <div class="product-info">
                                        <h4>${this.escapeHtml(product.product_name)}</h4>
                                        <p class="product-meta">
                                            <span class="code">Kod: ${this.escapeHtml(product.product_code)}</span>
                                            <span class="category">Kategori: ${this.escapeHtml(product.category)}</span>
                                            <span class="company">Şirket: ${this.escapeHtml(product.company_name)}</span>
                                            <span class="brand">Marka: ${this.escapeHtml(product.brand || 'Belirtilmemiş')}</span>
                                        </p>
                                    </div>
                                    <button class="btn btn-primary view-product">Görüntüle</button>
                                </div>
                            `;
                        }).join('')}
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Add event listeners
        modal.querySelector('.modal-close').addEventListener('click', () => modal.remove());
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.remove();
        });
        
        // Product selection
        modal.querySelectorAll('.view-product').forEach(button => {
            button.addEventListener('click', (e) => {
                const productElement = e.target.closest('.product-result');
                const uuid = productElement.getAttribute('data-uuid');
                window.location.href = `/Küresel/?page=product&uuid=${uuid}`;
            });
        });
    }
    
    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
        
        // Close button
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.remove();
        });
    }
    
    escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
}

// Initialize product search when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new ProductSearch();
});

// Load product details
async function loadProductDetails(productId, productUuid) {
    // Log parameters for debugging
    console.log('loadProductDetails called with ID:', productId, 'UUID:', productUuid);
    
    // Use the correct API endpoint based on what we're searching for
    let endpoint;
    const timestamp = new Date().getTime(); // Cache buster
    if (productUuid) {
        endpoint = `/Küresel/api/v1/products/get?uuid=${productUuid}&t=${timestamp}`;
    } else if (productId) {
        // Check if it's a numeric ID or product code/barcode
        if (!isNaN(productId)) {
            // It's a numeric ID
            endpoint = `/Küresel/api/v1/products/get?id=${productId}&t=${timestamp}`;
        } else if (productId.startsWith('PRD-')) {
            // It's a product code
            endpoint = `/Küresel/api/v1/products/get?product_code=${encodeURIComponent(productId)}&t=${timestamp}`;
        } else {
            // Assume it's a barcode
            endpoint = `/Küresel/api/v1/products/get?barcode=${encodeURIComponent(productId)}&t=${timestamp}`;
        }
    }
    
    console.log('API endpoint:', endpoint);
    
    // Show loading spinner
    document.getElementById('loading').style.display = 'flex';
    document.getElementById('product-content').style.display = 'none';
    document.getElementById('error-message').style.display = 'none';
    
    try {
        const response = await fetch(endpoint);
        
        // Check if response is OK
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        document.getElementById('loading').style.display = 'none';
        
        if (result.success) {
            displayProductDetails(result.data);
        } else {
            showError(result.message || 'Ürün bilgileri yüklenirken hata oluştu');
        }
        
    } catch (error) {
        console.error('Error loading product:', error);
        document.getElementById('loading').style.display = 'none';
        showError('Ürün bilgileri yüklenirken hata oluştu: ' + error.message);
    }
}

function displayProductDetails(product) {
    // Log product data for debugging
    console.log('Displaying product:', product);
    
    const content = document.getElementById('product-content');
    
    // Format product images - FIXED: Properly handle image paths
    let productImages = [];
    if (product.product_images && Array.isArray(product.product_images)) {
        productImages = product.product_images;
    } else if (typeof product.product_images === 'string') {
        try {
            productImages = JSON.parse(product.product_images);
        } catch (e) {
            productImages = [];
        }
    }
    
    // FIXED: Ensure proper image path handling
    let mainImage = '/Küresel/assets/images/product-placeholder.jpg';
    if (productImages.length > 0) {
        // Check if the image data is an array of objects or just a path
        if (typeof productImages[0] === 'object' && productImages[0].url) {
            // Use the URL from the image object
            mainImage = productImages[0].url;
        } else if (typeof productImages[0] === 'string') {
            // Handle string paths
            let imagePath = productImages[0];
            if (imagePath.startsWith('/')) {
                mainImage = '/Küresel' + imagePath;
            } else {
                mainImage = '/Küresel/' + imagePath;
            }
        }
    }
    
    content.innerHTML = `
        <div class="product-header">
            <div class="product-image">
                <img src="${mainImage}" alt="${product.product_name}" onerror="this.src='/Küresel/assets/images/product-placeholder.jpg'">
            </div>
            <div class="product-info">
                <h1>${product.product_name}</h1>
                <p class="product-code">Ürün Kodu: ${product.product_code}</p>
                <p class="product-category">${product.category}</p>
                <div class="product-description">
                    ${product.description || 'Ürün açıklaması bulunmuyor.'}
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
                    <strong>Marka:</strong> ${product.brand || 'Belirtilmemiş'}
                </div>
                <div class="overview-item">
                    <strong>Menşei:</strong> ${product.origin_country || 'Belirtilmemiş'}
                </div>
                <div class="overview-item">
                    <strong>Ağırlık:</strong> ${product.weight ? product.weight + ' kg' : 'Belirtilmemiş'}
                </div>
                <div class="overview-item">
                    <strong>Hacim:</strong> ${product.volume ? product.volume + ' L' : 'Belirtilmemiş'}
                </div>
                <div class="overview-item">
                    <strong>Alt Kategori:</strong> ${product.subcategory || 'Belirtilmemiş'}
                </div>
                <div class="overview-item">
                    <strong>Paketleme Türü:</strong> ${product.packaging_type || 'Belirtilmemiş'}
                </div>
                <div class="overview-item">
                    <strong>Raf Ömrü:</strong> ${product.shelf_life ? product.shelf_life + ' gün' : 'Belirtilmemiş'}
                </div>
                <div class="overview-item">
                    <strong>Hasat Sezonu:</strong> ${product.harvest_season || 'Belirtilmemiş'}
                </div>
            </div>
        </div>
        
        <div id="supply-chain-tab" class="product-tab-content">
            ${renderSupplyChainTab(product)}
        </div>
        
        <div id="impact-tab" class="product-tab-content">
            ${renderImpactScoresTab(product)}
        </div>
        
        <div id="certifications-tab" class="product-tab-content">
            ${renderCertificationsTab(product)}
        </div>
    `;
    
    content.style.display = 'block';
}

function renderSupplyChainTab(product) {
    if (!product.supply_chain_steps || product.supply_chain_steps.length === 0) {
        return '<p>Bu ürün için tedarik zinciri bilgisi bulunmuyor.</p>';
    }
    
    let supplyChainHtml = '<div class="supply-chain-steps">';
    
    product.supply_chain_steps.forEach(step => {
        supplyChainHtml += `
            <div class="supply-chain-step">
                <div class="step-header">
                    <h3>${step.step_name}</h3>
                    <span class="step-type">${step.step_type}</span>
                </div>
                <div class="step-details">
                    <p><strong>Şirket:</strong> ${step.company_name}</p>
                    <p><strong>Açıklama:</strong> ${step.step_description || 'Açıklama bulunmuyor'}</p>
                    ${step.address ? `<p><strong>Adres:</strong> ${step.address}</p>` : ''}
                    ${step.start_date ? `<p><strong>Başlangıç:</strong> ${new Date(step.start_date).toLocaleDateString('tr-TR')}</p>` : ''}
                    ${step.end_date ? `<p><strong>Bitiş:</strong> ${new Date(step.end_date).toLocaleDateString('tr-TR')}</p>` : ''}
                </div>
            </div>
        `;
    });
    
    supplyChainHtml += '</div>';
    return supplyChainHtml;
}

function renderImpactScoresTab(product) {
    if (!product.impact_scores) {
        return '<p>Bu ürün için etki skorları bulunmuyor.</p>';
    }
    
    const scores = product.impact_scores;
    
    return `
        <div class="impact-scores-container">
            <div class="overall-score">
                <h3>Genel Skor</h3>
                <div class="score-badge grade-${scores.overall_grade}">
                    ${scores.overall_score} <span class="grade">${scores.overall_grade}</span>
                </div>
            </div>
            
            <div class="scores-grid">
                <div class="score-category">
                    <h4>Çevresel Etki</h4>
                    <div class="score-item">
                        <span>Genel:</span>
                        <span class="score-value">${scores.environmental_score}</span>
                    </div>
                    <div class="score-item">
                        <span>Karbon Ayak İzi:</span>
                        <span class="score-value">${scores.carbon_footprint_score || 'N/A'}</span>
                    </div>
                    <div class="score-item">
                        <span>Su Kullanımı:</span>
                        <span class="score-value">${scores.water_footprint_score || 'N/A'}</span>
                    </div>
                </div>
                
                <div class="score-category">
                    <h4>Sosyal Etki</h4>
                    <div class="score-item">
                        <span>Genel:</span>
                        <span class="score-value">${scores.social_score}</span>
                    </div>
                    <div class="score-item">
                        <span>Adil Ücret:</span>
                        <span class="score-value">${scores.fair_wages_score || 'N/A'}</span>
                    </div>
                    <div class="score-item">
                        <span>Çalışma Koşulları:</span>
                        <span class="score-value">${scores.working_conditions_score || 'N/A'}</span>
                    </div>
                </div>
                
                <div class="score-category">
                    <h4>Şeffaflık</h4>
                    <div class="score-item">
                        <span>Genel:</span>
                        <span class="score-value">${scores.transparency_score}</span>
                    </div>
                    <div class="score-item">
                        <span>Veri Tamamlığı:</span>
                        <span class="score-value">${scores.data_completeness_score || 'N/A'}</span>
                    </div>
                </div>
            </div>
            
            <div class="metrics-section">
                <h4>Çevresel Metrikler</h4>
                <div class="metrics-grid">
                    <div class="metric-item">
                        <span>Toplam Karbon Ayak İzi:</span>
                        <span>${scores.total_carbon_footprint || 'N/A'} kg CO2</span>
                    </div>
                    <div class="metric-item">
                        <span>Toplam Su Kullanımı:</span>
                        <span>${scores.total_water_footprint || 'N/A'} L</span>
                    </div>
                    <div class="metric-item">
                        <span>Toplam Enerji Tüketimi:</span>
                        <span>${scores.total_energy_consumption || 'N/A'} kWh</span>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function renderCertificationsTab(product) {
    if (!product.certificates || Object.keys(product.certificates).length === 0) {
        return '<p>Bu ürün için sertifika bilgisi bulunmuyor.</p>';
    }
    
    let certsHtml = '<div class="certifications-container">';
    
    // If certificates is an array
    if (Array.isArray(product.certificates)) {
        product.certificates.forEach(cert => {
            certsHtml += `
                <div class="certificate-item">
                    <h4>${cert.name || cert.type || 'Sertifika'}</h4>
                    <p><strong>Veren Kurum:</strong> ${cert.issuer || 'Belirtilmemiş'}</p>
                    ${cert.certificate_number ? `<p><strong>Sertifika Numarası:</strong> ${cert.certificate_number}</p>` : ''}
                    ${cert.validity ? `<p><strong>Geçerlilik Tarihi:</strong> ${new Date(cert.validity).toLocaleDateString('tr-TR')}</p>` : ''}
                    ${cert.description ? `<p><strong>Açıklama:</strong> ${cert.description}</p>` : ''}
                </div>
            `;
        });
    } 
    // If certificates is an object
    else if (typeof product.certificates === 'object') {
        Object.entries(product.certificates).forEach(([key, cert]) => {
            certsHtml += `
                <div class="certificate-item">
                    <h4>${cert.name || cert.type || key}</h4>
                    <p><strong>Veren Kurum:</strong> ${cert.issuer || 'Belirtilmemiş'}</p>
                    ${cert.certificate_number ? `<p><strong>Sertifika Numarası:</strong> ${cert.certificate_number}</p>` : ''}
                    ${cert.validity ? `<p><strong>Geçerlilik Tarihi:</strong> ${new Date(cert.validity).toLocaleDateString('tr-TR')}</p>` : ''}
                    ${cert.description ? `<p><strong>Açıklama:</strong> ${cert.description}</p>` : ''}
                </div>
            `;
        });
    }
    
    certsHtml += '</div>';
    return certsHtml;
}

function showProductTab(tabName) {
    // Remove active class from all tabs
    document.querySelectorAll('.product-tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.product-tab-content').forEach(content => content.classList.remove('active'));
    
    // Add active class to selected tab
    document.querySelector(`button[onclick="showProductTab('${tabName}')"]`).classList.add('active');
    document.getElementById(`${tabName}-tab`).classList.add('active');
}

function showError(message) {
    document.getElementById('error-message').style.display = 'block';
    if (message) {
        document.querySelector('#error-message p').textContent = message;
    }
}