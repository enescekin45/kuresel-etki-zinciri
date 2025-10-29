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
require_once CLASSES_DIR . '/Validator.php';
require_once CLASSES_DIR . '/Product.php';

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
try {
    $validator->loadByUserId($currentUser->getId());
    $validatorData = $validator->toArray();
} catch (Exception $e) {
    // Validator profile not found
    $validatorData = null;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doğrulayıcı Paneli - Küresel Etki Zinciri</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="<?= $auth->generateCSRFToken() ?>">
</head>
<body>
    <?php include FRONTEND_DIR . '/components/header.php'; ?>
    
    <main class="dashboard validator-dashboard">
        <div class="container">
            <div class="dashboard-header">
                <h1>Doğrulayıcı Paneli</h1>
                <div class="dashboard-actions">
                    <a href="/Küresel/?page=validation&action=pending" class="btn btn-primary">Bekleyen Doğrulamalar</a>
                </div>
            </div>

            <div class="dashboard-grid">
                <!-- Validator Profile -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>Doğrulayıcı Profili</h2>
                        <button class="btn btn-outline btn-sm" onclick="editValidatorProfile()">Düzenle</button>
                    </div>
                    <div class="card-content">
                        <?php if ($validatorData): ?>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Uzmanlık Alanı:</label>
                                    <span><?= htmlspecialchars(implode(', ', $validatorData['specialization'] ?? ['Belirtilmemiş'])) ?></span>
                                </div>
                                <div class="info-item">
                                    <label>Sertifikalar:</label>
                                    <span><?= htmlspecialchars(implode(', ', $validatorData['credentials'] ?? ['Belirtilmemiş'])) ?></span>
                                </div>
                                <div class="info-item">
                                    <label>Deneyim (Yıl):</label>
                                    <span><?= htmlspecialchars($validatorData['experience_years'] ?? '0') ?></span>
                                </div>
                                <div class="info-item">
                                    <label>Güvenilirlik Skoru:</label>
                                    <span class="score"><?= number_format($validatorData['reputation_score'] ?? 0, 1) ?>/10</span>
                                </div>
                                <div class="info-item">
                                    <label>Durum:</label>
                                    <span class="status-badge status-<?= $validatorData['status'] ?? 'pending' ?>">
                                        <?= ucfirst($validatorData['status'] ?? 'Beklemede') ?>
                                    </span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <p>Doğrulayıcı profili bulunamadı. Lütfen profilinizi tamamlayın.</p>
                                <a href="/Küresel/?page=validator&action=setup" class="btn btn-primary">Profili Tamamla</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>Doğrulama İstatistikleri</h2>
                    </div>
                    <div class="card-content">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number" data-load="/Küresel/api/validator/stats/total">-</div>
                                <div class="stat-label">Toplam Doğrulama</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number" data-load="/Küresel/api/validator/stats/approved">-</div>
                                <div class="stat-label">Onaylanan</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number" data-load="/Küresel/api/validator/stats/rejected">-</div>
                                <div class="stat-label">Reddedilen</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number" data-load="/Küresel/api/validator/stats/pending">-</div>
                                <div class="stat-label">Bekleyen</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Validations -->
                <div class="dashboard-card full-width">
                    <div class="card-header">
                        <h2>Bekleyen Doğrulamalar</h2>
                        <a href="/Küresel/?page=validation&action=all" class="btn btn-outline btn-sm">Tümünü Gör</a>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table class="data-table" data-load="/Küresel/api/validator/validations/pending" data-template="validation-table">
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
                        <h2>Son Aktiviteler</h2>
                    </div>
                    <div class="card-content">
                        <div class="activity-feed" data-load="/Küresel/api/validator/activities/recent" data-template="activity-feed">
                            <div class="loading">Aktiviteler yükleniyor...</div>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>Performans Metrikleri</h2>
                    </div>
                    <div class="card-content">
                        <div class="performance-metrics" data-load="/Küresel/api/validator/performance" data-template="performance-metrics">
                            <div class="loading">Metrikler yükleniyor...</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3>Hızlı İşlemler</h3>
                <div class="actions-grid">
                    <button class="action-btn" onclick="startValidation()">
                        <span class="action-icon">🔍</span>
                        <span class="action-text">Doğrulama Başlat</span>
                    </button>
                    <button class="action-btn" onclick="viewReports()">
                        <span class="action-icon">📊</span>
                        <span class="action-text">Raporları Görüntüle</span>
                    </button>
                    <button class="action-btn" onclick="updateProfile()">
                        <span class="action-icon">👤</span>
                        <span class="action-text">Profili Güncelle</span>
                    </button>
                    <button class="action-btn" onclick="viewGuidelines()">
                        <span class="action-icon">📋</span>
                        <span class="action-text">Doğrulama Rehberi</span>
                    </button>
                </div>
            </div>
        </div>
    </main>

    <?php include FRONTEND_DIR . '/components/footer.php'; ?>
    
    <script src="assets/js/main.js"></script>
    <script src="assets/js/validator-dashboard.js"></script>
    
    <script>
    function editValidatorProfile() {
        window.location.href = '/Küresel/?page=validator&action=profile';
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
    
    // Initialize dashboard
    document.addEventListener('DOMContentLoaded', function() {
        // Don't override the loadDashboardData function from validator-dashboard.js
        // It will be called automatically
    });
    </script>
</body>
</html>