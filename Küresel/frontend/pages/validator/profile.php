<?php
/**
 * Validator Profile Dashboard Page
 * 
 * Displays validator statistics, charts, and performance metrics
 */

// Define required constants only if not already defined
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', __DIR__ . '/../../..');
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
require_once CLASSES_DIR . '/Validator.php';

$auth = Auth::getInstance();

// Redirect to login if not authenticated
if (!$auth->isLoggedIn()) {
    header('Location: /Küresel/?page=login');
    exit;
}

$currentUser = $auth->getCurrentUser();

// Only allow validator users to access this page
if (!$currentUser->isValidator()) {
    header('Location: /Küresel/');
    exit;
}

$validator = new Validator();
$validatorData = null;
$error = null;

try {
    $validator->loadByUserId($currentUser->getId());
    $validatorData = $validator->toArray(true); // Get detailed data
} catch (Exception $e) {
    $error = "Doğrulayıcı profili bulunamadı: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doğrulayıcı Profili - Küresel Etki Zinciri</title>
    <link rel="stylesheet" href="/Küresel/assets/css/style.css">
    <link rel="stylesheet" href="/Küresel/assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="<?= $auth->generateCSRFToken() ?>">
</head>
<body>
    <?php include FRONTEND_DIR . '/components/header.php'; ?>
    
    <main class="dashboard validator-dashboard">
        <div class="container">
            <div class="dashboard-header">
                <h1>Doğrulayıcı Profili</h1>
                <div class="dashboard-actions">
                    <a href="/Küresel/?page=validator" class="btn btn-outline">Geri Dön</a>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <p><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <div class="dashboard-grid">
                <!-- Profile Card -->
                <div class="dashboard-card full-width">
                    <div class="card-header">
                        <h2>Profil Bilgileri</h2>
                    </div>
                    <div class="card-content">
                        <div class="profile-content">
                            <div class="profile-card">
                                <div class="profile-header">
                                    <div class="profile-avatar">
                                        <div class="avatar-placeholder">
                                            <?php if (!empty($validatorData['profile_image'])): ?>
                                                <img src="<?= htmlspecialchars($validatorData['profile_image']) ?>" alt="Profil Resmi" class="profile-image">
                                            <?php else: ?>
                                                <span class="avatar-initials">
                                                    <?= strtoupper(substr($validatorData['validator_name'] ?? 'D', 0, 1)) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="profile-info">
                                        <h2><?= htmlspecialchars($validatorData['validator_name'] ?? $currentUser->getFullName()) ?></h2>
                                        <p class="profile-email">📧 <?= htmlspecialchars($currentUser->getEmail()) ?></p>
                                        <p class="profile-type">
                                            <span class="badge badge-validator">
                                                Doğrulayıcı
                                            </span>
                                        </p>
                                        <p class="profile-id">ID: <?= htmlspecialchars($validatorData['id'] ?? '-') ?></p>
                                        <div class="profile-stats">
                                            <div class="stat-item">
                                                <span class="stat-label">📅 Kayıt Tarihi</span>
                                                <span class="stat-value">
                                                    <?= !empty($validatorData['created_at']) ? date('d.m.Y', strtotime($validatorData['created_at'])) : '-' ?>
                                                </span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-label">⭐ Güvenilirlik Skoru</span>
                                                <span class="stat-value">
                                                    <?= number_format($validatorData['reputation_score'] ?? 0, 1) ?>/10
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="profile-details">
                                    <h3>📋 Profil Detayları</h3>
                                    <div class="profile-field">
                                        <label>Organizasyon Türü:</label>
                                        <span>
                                            <?php 
                                            $orgTypes = [
                                                'ngo' => 'STK',
                                                'certification_body' => 'Sertifikasyon Kurumu',
                                                'audit_firm' => 'Denetim Firması',
                                                'government' => 'Devlet Kurumu',
                                                'independent' => 'Bağımsız'
                                            ];
                                            echo htmlspecialchars($orgTypes[$validatorData['organization_type'] ?? ''] ?? ($validatorData['organization_type'] ?? 'Belirtilmemiş'));
                                            ?>
                                        </span>
                                    </div>
                                    
                                    <div class="profile-field">
                                        <label>Uzmanlık Alanları:</label>
                                        <span>
                                            <?= htmlspecialchars(implode(', ', $validatorData['specialization'] ?? ['Belirtilmemiş'])) ?>
                                        </span>
                                    </div>
                                    
                                    <div class="profile-field">
                                        <label>Deneyim (Yıl):</label>
                                        <span><?= htmlspecialchars($validatorData['experience_years'] ?? '0') ?></span>
                                    </div>
                                    
                                    <div class="profile-field">
                                        <label>Durum:</label>
                                        <span>
                                            <span class="badge badge-<?= $validatorData['status'] ?? 'pending' ?>">
                                                <?= ucfirst($validatorData['status'] ?? 'Beklemede') ?>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="profile-actions">
                                    <a href="/Küresel/?page=validator&action=profile&edit=1" class="btn btn-primary">⚙️ Profili Düzenle</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>📊 Doğrulama İstatistikleri</h2>
                    </div>
                    <div class="card-content">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number" id="total-validations">0</div>
                                <div class="stat-label">Toplam Doğrulama</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number" id="approved-validations">0</div>
                                <div class="stat-label">Onaylanan</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number" id="rejected-validations">0</div>
                                <div class="stat-label">Reddedilen</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number" id="pending-validations">0</div>
                                <div class="stat-label">Bekleyen</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Score -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>🏆 Performans Puanı</h2>
                    </div>
                    <div class="card-content">
                        <div class="performance-score">
                            <div class="score-circle">
                                <div class="score-value" id="reputation-score">
                                    <?= number_format($validatorData['reputation_score'] ?? 0, 1) ?>
                                </div>
                                <div class="score-label">/10</div>
                            </div>
                            <div class="score-description">
                                Güvenilirlik Puanı
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Validation Chart -->
                <div class="dashboard-card full-width">
                    <div class="card-header">
                        <h2>📈 Doğrulama Dağılımı</h2>
                    </div>
                    <div class="card-content">
                        <div class="chart-container">
                            <canvas id="validationChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Pending Validations -->
                <div class="dashboard-card full-width">
                    <div class="card-header">
                        <h2>⏳ Bekleyen Doğrulamalar</h2>
                        <a href="/Küresel/?page=validation&action=pending" class="btn btn-outline btn-sm">Tümünü Gör</a>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table class="data-table" id="pending-validations-table">
                                <thead>
                                    <tr>
                                        <th>Ürün</th>
                                        <th>Şirket</th>
                                        <th>Doğrulama Türü</th>
                                        <th>Gönderim Tarihi</th>
                                        <th>Öncelik</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="loading">Veriler yükleniyor...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>🕒 Son Aktiviteler</h2>
                    </div>
                    <div class="card-content">
                        <div class="activity-feed" id="activity-feed">
                            <div class="loading">Aktiviteler yükleniyor...</div>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>📈 Performans Metrikleri</h2>
                    </div>
                    <div class="card-content">
                        <div class="performance-metrics" id="performance-metrics">
                            <div class="loading">Metrikler yükleniyor...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include FRONTEND_DIR . '/components/footer.php'; ?>
    
    <!-- Chart.js Library -->
    <script src="/Küresel/assets/js/libs/chart.umd.min.js"></script>
    
    <style>
    .profile-content {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .profile-card {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .profile-header {
        display: flex;
        gap: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .profile-avatar {
        flex-shrink: 0;
    }

    .avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        font-weight: 600;
        overflow: hidden;
    }

    .profile-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-initials {
        font-size: 2.5rem;
    }

    .profile-info {
        flex: 1;
    }

    .profile-info h2 {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
        color: #2d3748;
    }

    .profile-email {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        color: #4a5568;
    }

    .profile-id {
        font-size: 0.9rem;
        color: #718096;
        margin-top: 0.5rem;
    }

    .profile-stats {
        display: flex;
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .profile-stats .stat-item {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        min-width: 120px;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #718096;
        display: block;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-weight: 600;
        color: #2d3748;
    }

    .profile-details {
        padding: 1rem 0;
    }

    .profile-details h3 {
        margin-bottom: 1.5rem;
        color: #2d3748;
        font-size: 1.2rem;
        position: relative;
        padding-bottom: 0.5rem;
    }

    .profile-details h3::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 2px;
    }

    .profile-field {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .profile-field:last-child {
        border-bottom: none;
    }

    .profile-field label {
        font-weight: 500;
        color: #4a5568;
    }

    .profile-field span {
        color: #2d3748;
        text-align: right;
    }

    .profile-actions {
        display: flex;
        gap: 1rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
    }

    .performance-score {
        text-align: center;
        padding: 1rem;
    }

    .score-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: conic-gradient(
            from 0deg,
            #48bb78 0deg,
            #48bb78 72deg,
            #ed8936 72deg,
            #ed8936 108deg,
            #f56565 108deg,
            #f56565 360deg
        );
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        position: relative;
    }

    .score-circle::before {
        content: '';
        position: absolute;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: white;
    }

    .score-value {
        position: relative;
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        z-index: 1;
    }

    .score-label {
        position: relative;
        font-size: 1rem;
        color: #718096;
        z-index: 1;
    }

    .score-description {
        font-weight: 500;
        color: #4a5568;
    }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    .activity-feed {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .activity-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border-radius: 8px;
        background: #f8fafc;
        transition: all 0.2s;
    }

    .activity-item:hover {
        background: #edf2f7;
        transform: translateX(5px);
    }

    .activity-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.25rem;
    }

    .activity-description {
        color: #4a5568;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .activity-time {
        color: #718096;
        font-size: 0.8rem;
    }

    .metrics-grid {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .metric-item {
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
    }

    .metric-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .metric-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #3182ce;
        margin-bottom: 0.25rem;
    }

    .metric-unit {
        font-size: 0.9rem;
        color: #718096;
    }

    .metric-target {
        font-size: 0.8rem;
        color: #718096;
        margin-bottom: 0.5rem;
    }

    .metric-progress {
        height: 8px;
        background: #e2e8f0;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #48bb78, #38a169);
        border-radius: 4px;
    }

    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
            align-items: center;
        }
        
        .profile-stats {
            justify-content: center;
        }
        
        .profile-field {
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .profile-field span {
            text-align: center;
        }
        
        .profile-actions {
            flex-direction: column;
        }
        
        .activity-item {
            flex-direction: column;
            text-align: center;
        }
        
        .score-circle {
            width: 100px;
            height: 100px;
        }
        
        .score-circle::before {
            width: 70px;
            height: 70px;
        }
    }
    </style>
    
    <script>
    // Load validator dashboard data
    document.addEventListener('DOMContentLoaded', function() {
        loadValidatorDashboardData();
    });

    async function loadValidatorDashboardData() {
        try {
            // Load statistics
            await loadStatistics();
            
            // Load validation chart
            await loadValidationChart();
            
            // Load pending validations
            await loadPendingValidations();
            
            // Load recent activities
            await loadRecentActivities();
            
            // Load performance metrics
            await loadPerformanceMetrics();
            
        } catch (error) {
            console.error('Error loading dashboard data:', error);
        }
    }

    async function loadStatistics() {
        try {
            // Load total validations
            const totalResponse = await fetch('/Küresel/api/validator/stats/total', {
                credentials: 'same-origin'
            });
            const totalData = await totalResponse.json();
            if (totalData.success) {
                document.getElementById('total-validations').textContent = totalData.data;
            }

            // Load approved validations
            const approvedResponse = await fetch('/Küresel/api/validator/stats/approved', {
                credentials: 'same-origin'
            });
            const approvedData = await approvedResponse.json();
            if (approvedData.success) {
                document.getElementById('approved-validations').textContent = approvedData.data;
            }

            // Load rejected validations
            const rejectedResponse = await fetch('/Küresel/api/validator/stats/rejected', {
                credentials: 'same-origin'
            });
            const rejectedData = await rejectedResponse.json();
            if (rejectedData.success) {
                document.getElementById('rejected-validations').textContent = rejectedData.data;
            }

            // Load pending validations
            const pendingResponse = await fetch('/Küresel/api/validator/stats/pending', {
                credentials: 'same-origin'
            });
            const pendingData = await pendingResponse.json();
            if (pendingData.success) {
                document.getElementById('pending-validations').textContent = pendingData.data;
            }
        } catch (error) {
            console.error('Error loading statistics:', error);
            // Show error in all stat elements
            document.querySelectorAll('.stat-number').forEach(el => {
                el.innerHTML = '<div class="error">Veri yüklenemedi</div>';
            });
        }
    }

    async function loadValidationChart() {
        try {
            // Get data for chart
            const totalResponse = await fetch('/Küresel/api/validator/stats/total', {
                credentials: 'same-origin'
            });
            const approvedResponse = await fetch('/Küresel/api/validator/stats/approved', {
                credentials: 'same-origin'
            });
            const rejectedResponse = await fetch('/Küresel/api/validator/stats/rejected', {
                credentials: 'same-origin'
            });
            const pendingResponse = await fetch('/Küresel/api/validator/stats/pending', {
                credentials: 'same-origin'
            });

            const totalData = await totalResponse.json();
            const approvedData = await approvedResponse.json();
            const rejectedData = await rejectedResponse.json();
            const pendingData = await pendingResponse.json();

            if (totalData.success && approvedData.success && rejectedData.success && pendingData.success) {
                const ctx = document.getElementById('validationChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Onaylanan', 'Reddedilen', 'Bekleyen'],
                        datasets: [{
                            data: [approvedData.data, rejectedData.data, pendingData.data],
                            backgroundColor: [
                                'rgba(72, 187, 120, 0.7)',
                                'rgba(245, 101, 101, 0.7)',
                                'rgba(237, 137, 54, 0.7)'
                            ],
                            borderColor: [
                                'rgba(72, 187, 120, 1)',
                                'rgba(245, 101, 101, 1)',
                                'rgba(237, 137, 54, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((context.raw / total) * 100);
                                        return `${context.label}: ${context.raw} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        } catch (error) {
            console.error('Error loading validation chart:', error);
        }
    }

    async function loadPendingValidations() {
        try {
            const response = await fetch('/Küresel/api/validator/validations/pending', {
                credentials: 'same-origin'
            });
            const result = await response.json();
            
            const container = document.querySelector('#pending-validations-table tbody');
            if (!container) return;
            
            if (result.success && result.data && result.data.length > 0) {
                container.innerHTML = result.data.map(validation => `
                    <tr>
                        <td>${escapeHtml(validation.product_name)}</td>
                        <td>${escapeHtml(validation.company_name)}</td>
                        <td>${escapeHtml(validation.validation_type)}</td>
                        <td>${formatDate(validation.requested_at)}</td>
                        <td><span class="priority-badge priority-${validation.priority}">Normal</span></td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="viewValidation(${validation.id})">İncele</button>
                        </td>
                    </tr>
                `).join('');
            } else {
                container.innerHTML = '<tr><td colspan="6" class="empty-state">Bekleyen doğrulama bulunamadı</td></tr>';
            }
        } catch (error) {
            console.error('Error loading pending validations:', error);
            const container = document.querySelector('#pending-validations-table tbody');
            if (container) {
                container.innerHTML = '<tr><td colspan="6" class="error">Veri yüklenemedi</td></tr>';
            }
        }
    }

    async function loadRecentActivities() {
        try {
            const response = await fetch('/Küresel/api/validator/activities/recent', {
                credentials: 'same-origin'
            });
            const result = await response.json();
            
            const container = document.getElementById('activity-feed');
            if (!container) return;
            
            if (result.success && result.data && result.data.length > 0) {
                container.innerHTML = result.data.map(activity => `
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
            } else {
                container.innerHTML = '<div class="empty-state">Henüz aktivite bulunmuyor</div>';
            }
        } catch (error) {
            console.error('Error loading activities:', error);
            const container = document.getElementById('activity-feed');
            if (container) {
                container.innerHTML = '<div class="error">Aktiviteler yüklenemedi</div>';
            }
        }
    }

    async function loadPerformanceMetrics() {
        try {
            const response = await fetch('/Küresel/api/validator/performance', {
                credentials: 'same-origin'
            });
            const result = await response.json();
            
            const container = document.getElementById('performance-metrics');
            if (!container) return;
            
            if (result.success && result.data && result.data.length > 0) {
                container.innerHTML = `
                    <div class="metrics-grid">
                        ${result.data.map(metric => `
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
            } else {
                container.innerHTML = '<div class="empty-state">Performans metrikleri bulunmuyor</div>';
            }
        } catch (error) {
            console.error('Error loading performance metrics:', error);
            const container = document.getElementById('performance-metrics');
            if (container) {
                container.innerHTML = '<div class="error">Metrikler yüklenemedi</div>';
            }
        }
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
    </script>
</body>
</html>