// Main JavaScript file for Küresel Etki Zinciri

// Global utilities
window.KuReselApp = {
    // API base URL
    apiBase: '/Küresel/api',
    
    // Make API requests
    async api(endpoint, options = {}) {
        // Handle absolute URLs - if endpoint starts with /company, /validator, or /auth, don't prepend apiBase
        let url;
        if (endpoint.startsWith('/company') || endpoint.startsWith('/validator') || endpoint.startsWith('/auth') || endpoint.startsWith('/admin')) {
            url = '/Küresel/api/v1' + endpoint;
        } else if (endpoint.startsWith('/')) {
            url = this.apiBase + endpoint;
        } else {
            url = this.apiBase + '/' + endpoint;
        }
        
        console.log('API call to endpoint:', endpoint, 'constructed URL:', url);
        
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };
        
        const finalOptions = { ...defaultOptions, ...options };
        
        try {
            const response = await fetch(url, finalOptions);
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'API request failed');
            }
            
            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    },
    
    // Show notification
    notify(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    },
    
    // Format date
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('tr-TR', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    },
    
    // Format number
    formatNumber(number, decimals = 2) {
        return new Intl.NumberFormat('tr-TR', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        }).format(number);
    },
    
    // Get CSRF token
    getCSRFToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }
};

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add CSRF token to all forms
    const csrfToken = KuReselApp.getCSRFToken();
    if (csrfToken) {
        document.querySelectorAll('form').forEach(form => {
            if (!form.querySelector('input[name="csrf_token"]')) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = 'csrf_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }
        });
    }
    
    // Handle form submissions
    document.querySelectorAll('form[data-api]').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const endpoint = this.getAttribute('data-api');
            const method = this.getAttribute('method') || 'POST';
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            try {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn ? submitBtn.textContent : '';
                
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Gönderiliyor...';
                }
                
                const result = await KuReselApp.api(endpoint, {
                    method: method,
                    body: JSON.stringify(data)
                });
                
                if (result.success) {
                    KuReselApp.notify(result.message || 'İşlem başarılı', 'success');
                    
                    // Handle redirect - prioritize API response redirect over form attribute
                    let redirect = null;
                    if (result.data && result.data.redirect) {
                        redirect = result.data.redirect;
                    } else {
                        redirect = this.getAttribute('data-redirect');
                    }
                    
                    if (redirect) {
                        setTimeout(() => {
                            window.location.href = redirect;
                        }, 1000); // Small delay to show success message
                    }
                } else {
                    KuReselApp.notify(result.message || 'İşlem başarısız', 'error');
                }
                
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
                
            } catch (error) {
                // Improved error message without red color
                KuReselApp.notify(error.message || 'İşlem sırasında hata oluştu', 'error');
                
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            }
        });
    });
    
    // Handle data loading
    document.querySelectorAll('[data-load]').forEach(element => {
        const endpoint = element.getAttribute('data-load');
        loadData(element, endpoint);
    });
});

// Load data into element
async function loadData(element, endpoint) {
    console.log('Loading data for element with endpoint:', endpoint);
    try {
        element.innerHTML = '<div class="loading">Yükleniyor...</div>';
        
        const data = await KuReselApp.api(endpoint);
        
        if (data.success) {
            // Handle different data types
            if (element.hasAttribute('data-template')) {
                const template = element.getAttribute('data-template');
                element.innerHTML = renderTemplate(template, data.data);
            } else {
                element.innerHTML = JSON.stringify(data.data, null, 2);
            }
        } else {
            // Improved error message without red color
            element.innerHTML = '<div class="error-message" style="padding: 1rem; text-align: center; color: #4a5568; background: #f7fafc; border-radius: 0.5rem;">Veri yüklenemedi: ' + (data.message || 'Bilinmeyen hata') + '</div>';
        }
        
    } catch (error) {
        console.error('Data loading error:', error);
        // Improved error message without red color
        element.innerHTML = '<div class="error-message" style="padding: 1rem; text-align: center; color: #4a5568; background: #f7fafc; border-radius: 0.5rem;">Veri yüklenirken hata oluştu: ' + error.message + '</div>';
    }
}

// Simple template renderer
function renderTemplate(templateName, data) {
    // This is a simple template system - can be enhanced
    switch (templateName) {
        case 'product-list':
            return renderProductList(data);
        case 'company-list':
            return renderCompanyList(data);
        case 'product-table':
            // Check if company dashboard function exists
            if (typeof window.renderProductTable === 'function') {
                return window.renderProductTable(data);
            }
            return '<div>Template not found</div>';
        case 'impact-summary':
            // Check if company dashboard function exists
            if (typeof window.renderImpactSummary === 'function') {
                return window.renderImpactSummary(data);
            }
            return '<div>Template not found</div>';
        case 'social-impact-summary':
            // Check if company dashboard function exists
            if (typeof window.renderSocialImpactSummary === 'function') {
                return window.renderSocialImpactSummary(data);
            }
            return '<div>Template not found</div>';
        default:
            return '<div>Template not found</div>';
    }
}

function renderProductList(products) {
    if (!products || products.length === 0) {
        return '<div class="empty-state">Ürün bulunamadı</div>';
    }
    
    return products.map(product => `
        <div class="product-item">
            <h3><a href="/product?uuid=${product.uuid}">${product.product_name}</a></h3>
            <p class="product-code">${product.product_code}</p>
            <p class="product-category">${product.category}</p>
        </div>
    `).join('');
}

function renderCompanyList(companies) {
    if (!companies || companies.length === 0) {
        return '<div class="empty-state">Şirket bulunamadı</div>';
    }
    
    return companies.map(company => `
        <div class="company-item">
            <h3>${company.company_name}</h3>
            <p class="company-type">${company.company_type}</p>
            <div class="company-scores">
                <span class="score">Şeffaflık: ${company.transparency_score}/100</span>
                <span class="score">İtibar: ${company.reputation_score}/100</span>
            </div>
        </div>
    `).join('');
}

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    }
}

// Export for use in other files
window.loadData = loadData;
window.renderTemplate = renderTemplate;