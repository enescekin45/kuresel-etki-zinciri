// Validator Dashboard JavaScript

// Load dashboard data when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
});

// Load all dashboard data
async function loadDashboardData() {
    try {
        // Load statistics
        await loadStatistics();
        
        // Load pending validations
        await loadPendingValidations();
        
        // Load recent activities
        await loadRecentActivities();
        
        // Load performance metrics
        await loadPerformanceMetrics();
        
    } catch (error) {
        console.error('Error loading dashboard data:', error);
        showDashboardError('Veriler yüklenirken hata oluştu');
    }
}

// Load statistics data
async function loadStatistics() {
    try {
        // Load total validations
        const totalResponse = await fetch('/Küresel/api/validator/stats/total', {
            credentials: 'same-origin'
        });
        const totalData = await totalResponse.json();
        if (totalData.success) {
            updateStatElement('total', totalData.data);
        }
        
        // Load approved validations
        const approvedResponse = await fetch('/Küresel/api/validator/stats/approved', {
            credentials: 'same-origin'
        });
        const approvedData = await approvedResponse.json();
        if (approvedData.success) {
            updateStatElement('approved', approvedData.data);
        }
        
        // Load rejected validations
        const rejectedResponse = await fetch('/Küresel/api/validator/stats/rejected', {
            credentials: 'same-origin'
        });
        const rejectedData = await rejectedResponse.json();
        if (rejectedData.success) {
            updateStatElement('rejected', rejectedData.data);
        }
        
        // Load pending validations
        const pendingResponse = await fetch('/Küresel/api/validator/stats/pending', {
            credentials: 'same-origin'
        });
        const pendingData = await pendingResponse.json();
        if (pendingData.success) {
            updateStatElement('pending', pendingData.data);
        }
        
    } catch (error) {
        console.error('Error loading statistics:', error);
        showStatError('İstatistikler yüklenemedi');
    }
}

// Load pending validations
async function loadPendingValidations() {
    try {
        const response = await fetch('/Küresel/api/validator/validations/pending', {
            credentials: 'same-origin'
        });
        const result = await response.json();
        
        const container = document.querySelector('.table-responsive tbody');
        if (!container) return;
        
        if (result.success) {
            if (result.data && result.data.length > 0) {
                container.innerHTML = renderPendingValidations(result.data);
            } else {
                container.innerHTML = '<tr><td colspan="6" class="empty-state">Bekleyen doğrulama bulunamadı</td></tr>';
            }
        } else {
            container.innerHTML = '<tr><td colspan="6" class="error">Veri yüklenemedi</td></tr>';
        }
        
    } catch (error) {
        console.error('Error loading pending validations:', error);
        const container = document.querySelector('.table-responsive tbody');
        if (container) {
            container.innerHTML = '<tr><td colspan="6" class="error">Veri yüklenemedi</td></tr>';
        }
    }
}

// Load recent activities
async function loadRecentActivities() {
    try {
        const response = await fetch('/Küresel/api/validator/activities/recent', {
            credentials: 'same-origin'
        });
        const result = await response.json();
        
        const container = document.querySelector('.activity-feed');
        if (!container) return;
        
        if (result.success) {
            if (result.data && result.data.length > 0) {
                container.innerHTML = renderActivities(result.data);
            } else {
                container.innerHTML = '<div class="empty-state">Henüz aktivite bulunmuyor</div>';
            }
        } else {
            container.innerHTML = '<div class="error">Aktiviteler yüklenemedi</div>';
        }
        
    } catch (error) {
        console.error('Error loading activities:', error);
        const container = document.querySelector('.activity-feed');
        if (container) {
            container.innerHTML = '<div class="error">Aktiviteler yüklenemedi</div>';
        }
    }
}

// Load performance metrics
async function loadPerformanceMetrics() {
    try {
        const response = await fetch('/Küresel/api/validator/performance', {
            credentials: 'same-origin'
        });
        const result = await response.json();
        
        const container = document.querySelector('.performance-metrics');
        if (!container) return;
        
        if (result.success) {
            if (result.data && result.data.length > 0) {
                container.innerHTML = renderPerformanceMetrics(result.data);
            } else {
                container.innerHTML = '<div class="empty-state">Performans metrikleri bulunmuyor</div>';
            }
        } else {
            container.innerHTML = '<div class="error">Metrikler yüklenemedi</div>';
        }
        
    } catch (error) {
        console.error('Error loading performance metrics:', error);
        const container = document.querySelector('.performance-metrics');
        if (container) {
            container.innerHTML = '<div class="error">Metrikler yüklenemedi</div>';
        }
    }
}

// Update stat element
function updateStatElement(elementId, value) {
    // For statistics, we need to match the correct data-load attribute
    let selector = '';
    switch(elementId) {
        case 'total':
            selector = '[data-load="/Küresel/api/validator/stats/total"]';
            break;
        case 'approved':
            selector = '[data-load="/Küresel/api/validator/stats/approved"]';
            break;
        case 'rejected':
            selector = '[data-load="/Küresel/api/validator/stats/rejected"]';
            break;
        case 'pending':
            selector = '[data-load="/Küresel/api/validator/stats/pending"]';
            break;
        default:
            selector = `[data-load="/Küresel/api/validator/stats/${elementId}"]`;
    }
    
    const element = document.querySelector(selector);
    if (element) {
        element.textContent = value;
    }
}

// Show stat error
function showStatError(message) {
    const statElements = document.querySelectorAll('[data-load^="/Küresel/api/validator/stats/"]');
    statElements.forEach(element => {
        element.innerHTML = `<div class="error">${message}</div>`;
    });
}

// Show dashboard error
function showDashboardError(message) {
    // Show error in all data containers
    const containers = document.querySelectorAll('[data-load]');
    containers.forEach(container => {
        container.innerHTML = `<div class="error">${message}</div>`;
    });
}

// Render pending validations table
function renderPendingValidations(validations) {
    return validations.map(validation => `
        <tr>
            <td>${escapeHtml(validation.product_name)}</td>
            <td>${escapeHtml(validation.company_name)}</td>
            <td>${escapeHtml(validation.validation_type)}</td>
            <td>${formatDate(validation.requested_at)}</td>
            <td><span class="priority-badge priority-normal">Normal</span></td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="viewValidation(${validation.id})">İncele</button>
            </td>
        </tr>
    `).join('');
}

// Render activities
function renderActivities(activities) {
    return activities.map(activity => `
        <div class="activity-item">
            <div class="activity-icon">
                ${activity.status === 'approved' ? '✅' : activity.status === 'rejected' ? '❌' : '⏳'}
            </div>
            <div class="activity-content">
                <div class="activity-title">${escapeHtml(activity.product_name)}</div>
                <div class="activity-description">
                    ${escapeHtml(activity.action)} - ${escapeHtml(activity.company_name)}
                </div>
                <div class="activity-time">${formatTime(activity.date)}</div>
            </div>
        </div>
    `).join('');
}

// Render performance metrics
function renderPerformanceMetrics(metrics) {
    return `
        <div class="metrics-grid">
            ${metrics.map(metric => `
                <div class="metric-item">
                    <div class="metric-label">${escapeHtml(metric.name)}</div>
                    <div class="metric-value">${metric.value}<span class="metric-unit">${escapeHtml(metric.unit)}</span></div>
                    <div class="metric-target">Hedef: ${metric.target}${escapeHtml(metric.unit)}</div>
                    <div class="metric-progress">
                        <div class="progress-bar" style="width: ${Math.min((metric.value / metric.target) * 100, 100)}%"></div>
                    </div>
                </div>
            `).join('')}
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
    return text.replace(/[&<>"']/g, m => map[m]);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('tr-TR');
}

function formatTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('tr-TR');
}

// Action functions
function viewValidation(validationId) {
    window.location.href = `/Küresel/?page=validation&action=view&id=${validationId}`;
}

function startValidation() {
    window.location.href = '/Küresel/?page=validation&action=pending';
}

function viewReports() {
    window.location.href = '/Küresel/?page=validator&action=reports';
}

function updateProfile() {
    window.location.href = '/Küresel/?page=validator&action=profile';
}

function viewGuidelines() {
    window.location.href = '/Küresel/?page=validator&action=guidelines';
}