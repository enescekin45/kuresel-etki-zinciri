<?php
/**
 * Add Product Page
 * 
 * Allows company users to add new products
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
$error = null;

try {
    $company->loadByUserId($currentUser->getId());
    $companyData = $company->toArray();
} catch (Exception $e) {
    $error = "Şirket profili bulunamadı: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Handle image upload if present
        $productImages = [];
        if (isset($_FILES['product_images']) && is_array($_FILES['product_images']['name'])) {
            $uploadedFiles = $_FILES['product_images'];
            $fileCount = min(count($uploadedFiles['name']), 5); // Limit to 5 images
            
            for ($i = 0; $i < $fileCount; $i++) {
                if ($uploadedFiles['error'][$i] === UPLOAD_ERR_OK) {
                    // Validate file type
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    if (!in_array($uploadedFiles['type'][$i], $allowedTypes)) {
                        $error = "Sadece JPEG, PNG ve GIF dosyaları yüklenebilir";
                        continue;
                    }
                    
                    // Validate file size (max 5MB)
                    if ($uploadedFiles['size'][$i] > 5 * 1024 * 1024) {
                        $error = "Dosya boyutu maksimum 5MB olabilir";
                        continue;
                    }
                    
                    // Create temporary file array for upload
                    $tempFile = [
                        'name' => $uploadedFiles['name'][$i],
                        'type' => $uploadedFiles['type'][$i],
                        'tmp_name' => $uploadedFiles['tmp_name'][$i],
                        'error' => $uploadedFiles['error'][$i],
                        'size' => $uploadedFiles['size'][$i]
                    ];
                    
                    // Save file temporarily and move to uploads directory
                    $extension = pathinfo($tempFile['name'], PATHINFO_EXTENSION);
                    $filename = 'product_' . time() . '_' . mt_rand(1000, 9999) . '.' . $extension;
                    $uploadDir = ROOT_DIR . '/uploads/products/';
                    
                    // Create upload directory if it doesn't exist
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $uploadPath = $uploadDir . $filename;
                    
                    // Move uploaded file
                    if (move_uploaded_file($tempFile['tmp_name'], $uploadPath)) {
                        $productImages[] = [
                            'filename' => $filename,
                            'url' => '/Küresel/uploads/products/' . $filename,
                            'original_name' => $tempFile['name']
                        ];
                    }
                }
            }
        }

        // Prepare product data
        $productData = [
            'company_id' => $company->getId(),
            'product_name' => trim($_POST['product_name'] ?? ''),
            'product_code' => trim($_POST['product_code'] ?? ''),
            'barcode' => trim($_POST['barcode'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'subcategory' => trim($_POST['subcategory'] ?? ''),
            'brand' => trim($_POST['brand'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'weight' => !empty($_POST['weight']) ? floatval($_POST['weight']) : null,
            'volume' => !empty($_POST['volume']) ? floatval($_POST['volume']) : null,
            'packaging_type' => trim($_POST['packaging_type'] ?? ''),
            'shelf_life' => !empty($_POST['shelf_life']) ? intval($_POST['shelf_life']) : null,
            'origin_country' => trim($_POST['origin_country'] ?? ''),
            'origin_region' => trim($_POST['origin_region'] ?? ''),
            'harvest_season' => trim($_POST['harvest_season'] ?? ''),
            'product_images' => $productImages,
            'status' => 'active'
        ];

        // Create product
        $product = new Product();
        $productId = $product->create($productData);
        
        // Redirect to product view page
        header("Location: /Küresel/?page=product&action=view&id={$productId}");
        exit;
        
    } catch (Exception $e) {
        $error = "Ürün eklenirken hata oluştu: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Ürün Ekle - Küresel Etki Zinciri</title>
    <link rel="stylesheet" href="/Küresel/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="<?= $auth->generateCSRFToken() ?>">
</head>
<body>
    <?php include FRONTEND_DIR . '/components/header.php'; ?>
    
    <main class="dashboard">
        <div class="container">
            <div class="dashboard-header">
                <h1>Yeni Ürün Ekle</h1>
                <div class="dashboard-actions">
                    <a href="/Küresel/?page=company" class="btn btn-outline">Geri Dön</a>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <p><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <div class="dashboard-card">
                <div class="card-header">
                    <h2>Ürün Bilgileri</h2>
                </div>
                <div class="card-content">
                    <form method="POST" class="product-form" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="product_name">Ürün Adı *</label>
                                <input type="text" id="product_name" name="product_name" 
                                       class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="product_code">Ürün Kodu</label>
                                <input type="text" id="product_code" name="product_code" 
                                       class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="barcode">Barkod</label>
                                <input type="text" id="barcode" name="barcode" 
                                       class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="category">Kategori *</label>
                                <select id="category" name="category" class="form-control" required>
                                    <option value="">Kategori Seçin</option>
                                    <option value="food">Gıda</option>
                                    <option value="clothing">Giyim</option>
                                    <option value="electronics">Elektronik</option>
                                    <option value="cosmetics">Kozmetik</option>
                                    <option value="furniture">Mobilya</option>
                                    <option value="other">Diğer</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="subcategory">Alt Kategori</label>
                                <input type="text" id="subcategory" name="subcategory" 
                                       class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="brand">Marka</label>
                                <input type="text" id="brand" name="brand" 
                                       class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Açıklama</label>
                            <textarea id="description" name="description" 
                                      class="form-control" rows="4"></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="weight">Ağırlık (kg)</label>
                                <input type="number" id="weight" name="weight" 
                                       class="form-control" step="0.001">
                            </div>
                            
                            <div class="form-group">
                                <label for="volume">Hacim (litre)</label>
                                <input type="number" id="volume" name="volume" 
                                       class="form-control" step="0.001">
                            </div>
                            
                            <div class="form-group">
                                <label for="shelf_life">Raf Ömrü (gün)</label>
                                <input type="number" id="shelf_life" name="shelf_life" 
                                       class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="packaging_type">Ambalaj Türü</label>
                                <input type="text" id="packaging_type" name="packaging_type" 
                                       class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="origin_country">Menşei Ülke</label>
                                <input type="text" id="origin_country" name="origin_country" 
                                       class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="origin_region">Menşei Bölge</label>
                                <input type="text" id="origin_region" name="origin_region" 
                                       class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="harvest_season">Hasat Sezonu</label>
                                <input type="text" id="harvest_season" name="harvest_season" 
                                       class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="product_images">Ürün Resimleri</label>
                            <input type="file" id="product_images" name="product_images[]" 
                                   class="form-control" multiple accept="image/*">
                            <small class="form-text">Birden fazla resim seçebilirsiniz (maksimum 5 adet)</small>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">➕ Ürünü Ekle</button>
                            <a href="/Küresel/?page=company" class="btn btn-outline">İptal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include FRONTEND_DIR . '/components/footer.php'; ?>
    
    <script src="/Küresel/assets/js/main.js"></script>
    <script>
        // Preview selected images
        document.getElementById('product_images').addEventListener('change', function(e) {
            const files = e.target.files;
            if (files.length > 5) {
                alert('En fazla 5 resim seçebilirsiniz');
                // Clear the selection
                e.target.value = '';
                return;
            }
            
            // You could add preview functionality here if desired
        });
    </script>
</body>
</html>