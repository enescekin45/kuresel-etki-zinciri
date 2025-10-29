<?php
/**
 * Product List Page
 * 
 * Shows all products for a company user
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
$products = [];

try {
    $company->loadByUserId($currentUser->getId());
    $companyData = $company->toArray();
    
    // Get all products for this company
    $products = $company->getAllProducts();
} catch (Exception $e) {
    $error = "Şirket profili bulunamadı: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Listesi - Küresel Etki Zinciri</title>
    <link rel="stylesheet" href="/Küresel/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="<?= $auth->generateCSRFToken() ?>">
</head>
<body>
    <?php include FRONTEND_DIR . '/components/header.php'; ?>
    
    <main class="dashboard">
        <div class="container">
            <div class="dashboard-header">
                <h1>Ürün Listesi</h1>
                <div class="dashboard-actions">
                    <a href="/Küresel/?page=product&action=add" class="btn btn-primary">Yeni Ürün Ekle</a>
                    <a href="/Küresel/?page=company" class="btn btn-outline">Geri Dön</a>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <p><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <div class="dashboard-card full-width">
                <div class="card-header">
                    <h2>Tüm Ürünler</h2>
                    <a href="/Küresel/?page=product&action=add" class="btn btn-primary">Yeni Ürün Ekle</a>
                </div>
                <div class="card-content">
                    <?php if (empty($products)): ?>
                        <div class="empty-state">
                            <p>Henüz ürün eklenmemiş.</p>
                            <a href="/Küresel/?page=product&action=add" class="btn btn-primary">İlk Ürünü Ekle</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Ürün Resmi</th>
                                        <th>Ürün Adı</th>
                                        <th>Kategori</th>
                                        <th>Durum</th>
                                        <th>QR Tarama</th>
                                        <th>Ekleme Tarihi</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td style="width: 60px;">
                                                <?php 
                                                $images = isset($product['product_images']) ? $product['product_images'] : [];
                                                // Ensure $images is an array
                                                if (is_string($images)) {
                                                    $images = json_decode($images, true) ?: [];
                                                }
                                                if (!empty($images) && isset($images[0])): 
                                                    // Check if the first image is an array with 'url' key or just a string
                                                    $imageUrl = is_array($images[0]) && isset($images[0]['url']) ? $images[0]['url'] : (is_string($images[0]) ? $images[0] : '');
                                                    if (!empty($imageUrl)): 
                                                ?>
                                                    <img src="<?= htmlspecialchars($imageUrl) ?>" alt="<?= htmlspecialchars($product['product_name'] ?? 'Ürün') ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" onerror="this.parentElement.innerHTML='<div style=\'width: 50px; height: 50px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 4px;\'><span style=\'font-size: 12px; color: #999;\'>Resim Yok</span></div>'">
                                                <?php else: ?>
                                                    <div style="width: 50px; height: 50px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                                        <span style="font-size: 12px; color: #999;">Resim Yok</span>
                                                    </div>
                                                <?php 
                                                    endif;
                                                else: 
                                                ?>
                                                    <div style="width: 50px; height: 50px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                                        <span style="font-size: 12px; color: #999;">Resim Yok</span>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div style="font-weight: 500;"><?= htmlspecialchars($product['product_name'] ?? 'Belirtilmemiş') ?></div>
                                                <div style="font-size: 0.875rem; color: #718096;"><?= htmlspecialchars($product['product_code'] ?? '') ?></div>
                                            </td>
                                            <td><?= htmlspecialchars($product['category'] ?? 'Belirtilmemiş') ?></td>
                                            <td>
                                                <span class="status-badge status-<?= $product['status'] ?? 'pending' ?>">
                                                    <?= $product['status'] === 'active' ? 'Aktif' : 
                                                       ($product['status'] === 'inactive' ? 'Pasif' : 
                                                       ($product['status'] === 'pending' ? 'Beklemede' : 
                                                       ($product['status'] === 'discontinued' ? 'Üretim Durduruldu' : $product['status']))) ?>
                                                </span>
                                            </td>
                                            <td><?= $product['qr_scans'] ?? 0 ?></td>
                                            <td><?= !empty($product['created_at']) ? date('d.m.Y', strtotime($product['created_at'])) : 'Belirtilmemiş' ?></td>
                                            <td>
                                                <div style="display: flex; gap: 0.25rem; flex-wrap: wrap;">
                                                    <a href="/Küresel/?page=product&action=edit&id=<?= $product['id'] ?>" class="btn btn-sm btn-warning">Düzenle</a>
                                                    <a href="/Küresel/?page=product&action=view&id=<?= $product['id'] ?>" class="btn btn-sm btn-primary">İncele</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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