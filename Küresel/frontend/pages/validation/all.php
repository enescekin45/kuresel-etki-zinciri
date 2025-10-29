<?php
/**
 * All Validations Page
 * 
 * Displays all validation requests for validators
 */

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
    $validatorData = $validator->toArray();
} catch (Exception $e) {
    // Validator profile not found
    $validatorData = null;
}

// Get all validations with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$validations = [];
$totalValidations = 0;

try {
    if ($validatorData) {
        $validations = $validator->getValidationHistory($page, $limit);
        // For total count, we would need to implement a method in Validator class
        // For now, we'll just use the count of current page
        $totalValidations = count($validations);
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tüm Doğrulamalar - Küresel Etki Zinciri</title>
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
                <h1>Tüm Doğrulamalar</h1>
                <div class="dashboard-actions">
                    <a href="/Küresel/?page=validator" class="btn btn-outline">Geri Dön</a>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <p>Hata: <?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <div class="dashboard-card">
                <div class="card-header">
                    <h2>Doğrulama Geçmişi</h2>
                </div>
                <div class="card-content">
                    <?php if (empty($validations)): ?>
                        <div class="empty-state">
                            <p>Henüz doğrulama talebi bulunmamaktadır.</p>
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
                                        <th>Gönderim Tarihi</th>
                                        <th>Sonuç</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($validations as $validation): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($validation['product_name']) ?></td>
                                            <td><?= htmlspecialchars($validation['company_name']) ?></td>
                                            <td><?= htmlspecialchars($validation['validation_type']) ?></td>
                                            <td>
                                                <span class="status-badge status-<?= $validation['status'] ?>">
                                                    <?= ucfirst($validation['status']) ?>
                                                </span>
                                            </td>
                                            <td><?= date('d.m.Y H:i', strtotime($validation['requested_at'])) ?></td>
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
                                            <td>
                                                <a href="/Küresel/?page=validation&action=review&id=<?= $validation['id'] ?>" 
                                                   class="btn btn-sm btn-primary">İncele</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="/Küresel/?page=validation&action=all&page=<?= $page - 1 ?>" class="btn btn-outline">Önceki</a>
                            <?php endif; ?>
                            
                            <span class="page-info">Sayfa <?= $page ?></span>
                            
                            <?php if (count($validations) == $limit): ?>
                                <a href="/Küresel/?page=validation&action=all&page=<?= $page + 1 ?>" class="btn btn-outline">Sonraki</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include FRONTEND_DIR . '/components/footer.php'; ?>
    
    <script src="/Küresel/assets/js/main.js"></script>
</body>
</html>