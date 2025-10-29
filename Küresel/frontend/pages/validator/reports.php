<?php
/**
 * Validator Reports Page
 * 
 * Displays detailed reports and statistics for validators
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
require_once CLASSES_DIR . '/Database.php';

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
try {
    $validator->loadByUserId($currentUser->getId());
    $validatorData = $validator->toArray(true); // Get detailed data
    $statistics = $validator->getStatistics();
} catch (Exception $e) {
    // Validator profile not found
    $validatorData = null;
    $statistics = null;
    $error = $e->getMessage();
}

// Get validation history for reports
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$validations = [];

try {
    if ($validatorData) {
        $validations = $validator->getValidationHistory($page, $limit);
    }
} catch (Exception $e) {
    if (!isset($error)) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doğrulayıcı Raporları - Küresel Etki Zinciri</title>
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
                <h1>📊 Doğrulayıcı Raporları</h1>
                <div class="dashboard-actions">
                    <a href="/Küresel/?page=validator" class="btn btn-outline">Geri Dön</a>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <p>Hata: <?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <?php if ($validatorData && $statistics): ?>
                <!-- Statistics Overview -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>📈 İstatistikler Özeti</h2>
                    </div>
                    <div class="card-content">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number"><?= $statistics['total_validations'] ?? 0 ?></div>
                                <div class="stat-label">Toplam Doğrulama</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number"><?= $statistics['completed_validations'] ?? 0 ?></div>
                                <div class="stat-label">Tamamlanan</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number"><?= $statistics['success_rate'] ?? 0 ?>%</div>
                                <div class="stat-label">Başarı Oranı</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number"><?= $statistics['avg_response_time'] ?? 0 ?> saat</div>
                                <div class="stat-label">Ort. Yanıt Süresi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Reports -->
                <div class="dashboard-grid">
                    <!-- Validation Results Distribution -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2>✅ Doğrulama Sonuçları</h2>
                        </div>
                        <div class="card-content">
                            <?php
                            // Get validation results distribution
                            $db = Database::getInstance();
                            $resultsSql = "SELECT validation_result, COUNT(*) as count 
                                          FROM validation_records 
                                          WHERE validator_id = ? 
                                          GROUP BY validation_result";
                            $resultsData = $db->fetchAll($resultsSql, [$validator->getId()]);
                            
                            $results = [
                                'approved' => 0,
                                'rejected' => 0,
                                'needs_clarification' => 0
                            ];
                            
                            foreach ($resultsData as $row) {
                                $results[$row['validation_result']] = $row['count'];
                            }
                            ?>
                            <div class="chart-container">
                                <div class="chart-bar">
                                    <div class="chart-label">Onaylanan</div>
                                    <div class="chart-bar-container">
                                        <div class="chart-bar-fill" style="width: <?= ($results['approved'] > 0) ? ($results['approved'] / max(array_values($results)) * 100) : 0 ?>%"></div>
                                    </div>
                                    <div class="chart-value"><?= $results['approved'] ?></div>
                                </div>
                                <div class="chart-bar">
                                    <div class="chart-label">Reddedilen</div>
                                    <div class="chart-bar-container">
                                        <div class="chart-bar-fill" style="width: <?= ($results['rejected'] > 0) ? ($results['rejected'] / max(array_values($results)) * 100) : 0 ?>%"></div>
                                    </div>
                                    <div class="chart-value"><?= $results['rejected'] ?></div>
                                </div>
                                <div class="chart-bar">
                                    <div class="chart-label">Açıklama Gerekli</div>
                                    <div class="chart-bar-container">
                                        <div class="chart-bar-fill" style="width: <?= ($results['needs_clarification'] > 0) ? ($results['needs_clarification'] / max(array_values($results)) * 100) : 0 ?>%"></div>
                                    </div>
                                    <div class="chart-value"><?= $results['needs_clarification'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Validation Types Distribution -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h2>📋 Doğrulama Türleri</h2>
                        </div>
                        <div class="card-content">
                            <?php
                            // Get validation types distribution
                            $typesSql = "SELECT validation_type, COUNT(*) as count 
                                        FROM validation_records 
                                        WHERE validator_id = ? 
                                        GROUP BY validation_type";
                            $typesData = $db->fetchAll($typesSql, [$validator->getId()]);
                            ?>
                            <div class="chart-container">
                                <?php foreach ($typesData as $row): ?>
                                    <div class="chart-bar">
                                        <div class="chart-label"><?= ucfirst(str_replace('_', ' ', $row['validation_type'])) ?></div>
                                        <div class="chart-bar-container">
                                            <div class="chart-bar-fill" style="width: <?= ($row['count'] > 0) ? ($row['count'] / max(array_column($typesData, 'count')) * 100) : 0 ?>%"></div>
                                        </div>
                                        <div class="chart-value"><?= $row['count'] ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Validations Table -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>📝 Son Doğrulamalar</h2>
                    </div>
                    <div class="card-content">
                        <?php if (empty($validations)): ?>
                            <div class="empty-state">
                                <p>Henüz doğrulama kaydı bulunmamaktadır.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Ürün</th>
                                            <th>Şirket</th>
                                            <th>Doğrulama Türü</th>
                                            <th>Durum</th>
                                            <th>Sonuç</th>
                                            <th>Tarih</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($validations as $validation): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($validation['product_name']) ?></td>
                                                <td><?= htmlspecialchars($validation['company_name']) ?></td>
                                                <td><?= htmlspecialchars(str_replace('_', ' ', $validation['validation_type'])) ?></td>
                                                <td>
                                                    <span class="status-badge status-<?= $validation['status'] ?>">
                                                        <?= ucfirst($validation['status']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($validation['validation_result'] === 'approved'): ?>
                                                        <span class="status-badge status-success">Onaylandı</span>
                                                    <?php elseif ($validation['validation_result'] === 'rejected'): ?>
                                                        <span class="status-badge status-error">Reddedildi</span>
                                                    <?php elseif ($validation['validation_result'] === 'needs_clarification'): ?>
                                                        <span class="status-badge status-warning">Açıklama Gerekli</span>
                                                    <?php else: ?>
                                                        <span class="status-badge status-pending">Beklemede</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('d.m.Y H:i', strtotime($validation['requested_at'])) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="pagination">
                                <?php if ($page > 1): ?>
                                    <a href="/Küresel/?page=validator&action=reports&page=<?= $page - 1 ?>" class="btn btn-outline">Önceki</a>
                                <?php endif; ?>
                                
                                <span class="page-info">Sayfa <?= $page ?></span>
                                
                                <?php if (count($validations) == $limit): ?>
                                    <a href="/Küresel/?page=validator&action=reports&page=<?= $page + 1 ?>" class="btn btn-outline">Sonraki</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="dashboard-card">
                    <div class="card-content">
                        <div class="empty-state">
                            <p>Doğrulayıcı profili bulunamadı veya istatistikler yüklenemedi.</p>
                            <?php if (isset($error)): ?>
                                <p>Hata: <?= htmlspecialchars($error) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include FRONTEND_DIR . '/components/footer.php'; ?>
    
    <script src="/Küresel/assets/js/main.js"></script>
</body>
</html>