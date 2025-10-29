// Company Dashboard JavaScript

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add event listener for edit button
    const editButton = document.querySelector('.card-header .btn-outline');
    if (editButton) {
        editButton.addEventListener('click', showEditCompanyModal);
    }
});

// Show company edit modal
function showEditCompanyModal() {
    // Get current company data from the page
    const companyNameElement = Array.from(document.querySelectorAll('.info-item')).find(item => 
        item.querySelector('label')?.textContent.trim() === 'Şirket Adı:'
    );
    const companyName = companyNameElement ? companyNameElement.querySelector('span').textContent.trim() : '';
    
    const industrySectorElement = Array.from(document.querySelectorAll('.info-item')).find(item => 
        item.querySelector('label')?.textContent.trim() === 'Sektör:'
    );
    const industrySector = industrySectorElement ? industrySectorElement.querySelector('span').textContent.trim() : '';
    
    // Create modal HTML
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h2>Şirket Bilgilerini Düzenle</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <form id="edit-company-form" class="modal-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_company_name">Şirket Adı:</label>
                        <input type="text" id="edit_company_name" name="company_name" value="${companyName}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_industry_sector">Sektör:</label>
                        <input type="text" id="edit_industry_sector" name="industry_sector" value="${industrySector}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit_address_line1">Adres Satırı 1:</label>
                        <input type="text" id="edit_address_line1" name="address_line1" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit_address_line2">Adres Satırı 2:</label>
                        <input type="text" id="edit_address_line2" name="address_line2" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit_city">Şehir:</label>
                        <input type="text" id="edit_city" name="city" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit_postal_code">Posta Kodu:</label>
                        <input type="text" id="edit_postal_code" name="postal_code" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit_country">Ülke:</label>
                        <input type="text" id="edit_country" name="country" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeModal()">İptal</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    `;
    
    document.body.appendChild(modal);
    document.body.classList.add('modal-open');
    
    // Add form submit handler
    const form = document.getElementById('edit-company-form');
    form.addEventListener('submit', handleCompanyEditSubmit);
}

// Handle company edit form submission
async function handleCompanyEditSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    try {
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Kaydediliyor...';
        
        // Send data to API endpoint
        const response = await fetch('/Küresel/api/v1/company/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Close modal and show success message
            closeModal();
            alert('Şirket bilgileri başarıyla güncellendi!');
            
            // Reload the page to show updated information
            window.location.reload();
        } else {
            throw new Error(result.message || 'Güncelleme başarısız oldu');
        }
        
    } catch (error) {
        // Improved error message without red color
        alert('Şirket bilgileri güncellenirken hata oluştu: ' + error.message);
    } finally {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    }
}

// Close modal
function closeModal() {
    const modal = document.querySelector('.modal');
    if (modal) {
        modal.remove();
        document.body.classList.remove('modal-open');
    }
}

// Load dashboard data
function loadDashboardData() {
    // This will be handled by the main.js data loading system
    console.log('Loading company dashboard data...');
}

// Render product table template - Enhanced version
function renderProductTable(products) {
    if (!products || products.length === 0) {
        return '<tr><td colspan="7" class="empty-state">Ürün bulunamadı</td></tr>';
    }
    
    return products.map(product => `
        <tr>
            <td>
                ${product.product_images && product.product_images.length > 0 ? 
                    (typeof product.product_images[0] === 'object' && product.product_images[0].url ? 
                        `<img src="${escapeHtml(product.product_images[0].url)}" alt="${escapeHtml(product.product_name || 'Ürün')}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">` : 
                        (typeof product.product_images[0] === 'string' ? 
                            `<img src="${escapeHtml(product.product_images[0])}" alt="${escapeHtml(product.product_name || 'Ürün')}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">` : 
                            `<div style="width: 40px; height: 40px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                <span style="font-size: 10px; color: #999;">Resim Yok</span>
                            </div>`)) : 
                    `<div style="width: 40px; height: 40px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                        <span style="font-size: 10px; color: #999;">Resim Yok</span>
                    </div>`}
            </td>
            <td>
                <div style="font-weight: 500;">${escapeHtml(product.product_name || 'Belirtilmemiş')}</div>
            </td>
            <td>${escapeHtml(product.category || 'Belirtilmemiş')}</td>
            <td>
                <span class="status-badge status-${product.status || 'pending'}">
                    ${getStatusText(product.status || 'pending')}
                </span>
            </td>
            <td>${product.qr_scans || 0}</td>
            <td>${formatDate(product.created_at || new Date())}</td>
            <td>
                <div style="display: flex; gap: 0.25rem;">
                    <a href="/Küresel/?page=product&action=edit&id=${product.id}" class="btn btn-sm btn-warning">Düzenle</a>
                    <a href="/Küresel/?page=product&action=view&id=${product.id}" class="btn btn-sm btn-primary">İncele</a>
                </div>
            </td>
        </tr>
    `).join('');
}

// Render impact summary template with improved styling and better empty state
function renderImpactSummary(impactData) {
    if (!impactData) {
        return `
        <div class="empty-state-card" style="text-align: center; padding: 2rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🌍</div>
            <h3 style="margin-bottom: 0.5rem; color: #2d3748;">Çevresel Etki Verisi Yok</h3>
            <p style="color: #718096; margin-bottom: 1rem;">Şirketinizin çevresel etkisini ölçmek için ürünler ekleyin.</p>
            <a href="/Küresel/?page=product&action=add" class="btn btn-primary" style="display: inline-block;">İlk Ürünü Ekle</a>
        </div>
        `;
    }
    
    return `
        <div class="impact-metrics">
            <div class="metric-item">
                <div class="metric-label">Çevresel Skor</div>
                <div class="metric-value">${impactData.environmental_score || 0}<span class="metric-unit">/100</span></div>
            </div>
            <div class="metric-item">
                <div class="metric-label">Karbon Ayak İzi</div>
                <div class="metric-value">${impactData.carbon_footprint || 0}<span class="metric-unit">kg CO2</span></div>
            </div>
            <div class="metric-item">
                <div class="metric-label">Su Kullanımı</div>
                <div class="metric-value">${impactData.water_footprint || 0}<span class="metric-unit">L</span></div>
            </div>
        </div>
    `;
}

// Render social impact summary template with improved styling and better empty state
function renderSocialImpactSummary(socialData) {
    if (!socialData) {
        return `
        <div class="empty-state-card" style="text-align: center; padding: 2rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">👥</div>
            <h3 style="margin-bottom: 0.5rem; color: #2d3748;">Sosyal Etki Verisi Yok</h3>
            <p style="color: #718096; margin-bottom: 1rem;">Şirketinizin sosyal etkisini ölçmek için ürünler ekleyin.</p>
            <a href="/Küresel/?page=product&action=add" class="btn btn-primary" style="display: inline-block;">İlk Ürünü Ekle</a>
        </div>
        `;
    }
    
    return `
        <div class="social-metrics">
            <div class="metric-item">
                <div class="metric-label">Sosyal Skor</div>
                <div class="metric-value">${socialData.social_score || 0}<span class="metric-unit">/100</span></div>
            </div>
            <div class="metric-item">
                <div class="metric-label">Adil Ücret</div>
                <div class="metric-value">${socialData.fair_wages || 0}<span class="metric-unit">/100</span></div>
            </div>
            <div class="metric-item">
                <div class="metric-label">Çalışma Koşulları</div>
                <div class="metric-value">${socialData.working_conditions || 0}<span class="metric-unit">/100</span></div>
            </div>
        </div>
    `;
}

// Helper functions
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

function getStatusText(status) {
    const statusMap = {
        'active': 'Aktif',
        'inactive': 'Pasif',
        'pending': 'Beklemede',
        'discontinued': 'Üretim Durduruldu'
    };
    return statusMap[status] || status;
}

// Add template functions to window object for global access
window.renderProductTable = renderProductTable;
window.renderImpactSummary = renderImpactSummary;
window.renderSocialImpactSummary = renderSocialImpactSummary;
window.showEditCompanyModal = showEditCompanyModal;
window.closeModal = closeModal;