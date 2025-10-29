<?php
// Debug version of product edit to see what's happening with file uploads

// Define required constants only if not already defined
if (!defined('ROOT_DIR')) {
    define('ROOT_DIR', __DIR__ . '/..');
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
$product = new Product();
$error = null;
$success = null;

try {
    $company->loadByUserId($currentUser->getId());
    
    // Get product ID from URL parameter
    $productId = $_GET['id'] ?? null;
    
    if (!$productId) {
        throw new Exception("Ürün ID belirtilmedi");
    }
    
    // Load the product
    $product->loadById($productId);
    
    // Verify that this product belongs to the current company
    if ($product->getCompanyId() != $company->getId()) {
        throw new Exception("Bu ürünü düzenleme yetkiniz yok");
    }
    
    // Get product data for the form
    $productData = $product->toArray(true);
    
} catch (Exception $e) {
    $error = "Ürün bilgileri yüklenirken hata oluştu: " . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    echo "<h2>Debug Info</h2>";
    echo "<pre>";
    echo "POST data:\n";
    print_r($_POST);
    echo "\nFILES data:\n";
    print_r($_FILES);
    echo "</pre>";
    
    try {
        // Handle image upload if present
        $productImages = [];
        
        // First, get existing images - FIXED: Properly decode existing images
        $existingImages = $productData['product_images'] ?? [];
        // Ensure existingImages is always an array
        if (is_string($existingImages)) {
            $existingImages = json_decode($existingImages, true) ?: [];
        } elseif (!is_array($existingImages)) {
            $existingImages = [];
        }
        
        echo "<pre>";
        echo "Existing images:\n";
        print_r($existingImages);
        echo "</pre>";
        
        // Handle image removal
        $productImages = [];
        if (isset($_POST['remove_images']) && is_array($_POST['remove_images'])) {
            foreach ($existingImages as $image) {
                if (!in_array($image['filename'], $_POST['remove_images'])) {
                    $productImages[] = $image;
                } else {
                    // Delete the file from the server
                    $uploadPath = ROOT_DIR . '/uploads/products/' . $image['filename'];
                    if (file_exists($uploadPath)) {
                        unlink($uploadPath);
                    }
                }
            }
        } else {
            $productImages = $existingImages;
        }
        
        echo "<pre>";
        echo "Product images after removal handling:\n";
        print_r($productImages);
        echo "</pre>";
        
        // Handle new image uploads
        $hasFiles = false;
        if (isset($_FILES['product_images']) && is_array($_FILES['product_images']['name'])) {
            // Check if any file was actually uploaded
            foreach ($_FILES['product_images']['error'] as $error) {
                if ($error === UPLOAD_ERR_OK) {
                    $hasFiles = true;
                    break;
                }
            }
        }
        
        echo "<pre>";
        echo "Has files to upload: " . ($hasFiles ? "Yes" : "No") . "\n";
        echo "</pre>";
        
        if ($hasFiles) {
            $uploadedFiles = $_FILES['product_images'];
            $fileCount = min(count($uploadedFiles['name']), max(0, 5 - count($productImages))); // Limit to 5 images total
            
            echo "<pre>";
            echo "File count: " . $fileCount . "\n";
            echo "</pre>";
            
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
        
        echo "<pre>";
        echo "Final product images:\n";
        print_r($productImages);
        echo "</pre>";

        // Prepare product data for update
        $updateData = [
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
            'status' => trim($_POST['status'] ?? 'active')
        ];

        echo "<pre>";
        echo "Update data:\n";
        print_r($updateData);
        echo "</pre>";

        // Update product
        $product->update($updateData);
        
        $success = "Ürün başarıyla güncellendi!";
        
        // Reload product data
        $productData = $product->toArray(true);
        
    } catch (Exception $e) {
        $error = "Ürün güncellenirken hata oluştu: " . $e->getMessage();
    }
    
    echo "<a href='/Küresel/?page=product&action=edit&id=" . $productId . "'>Back to edit page</a>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Product Edit - Küresel Etki Zinciri</title>
    <link rel="stylesheet" href="/Küresel/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="<?= $auth->generateCSRFToken() ?>">
</head>
<body>
    <?php include FRONTEND_DIR . '/components/header.php'; ?>
    
    <main class="dashboard">
        <div class="container">
            <div class="dashboard-header">
                <h1>Debug Product Edit</h1>
                <div class="dashboard-actions">
                    <a href="/Küresel/?page=product&action=list" class="btn btn-outline">Geri Dön</a>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <p><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <p><?= htmlspecialchars($success) ?></p>
                </div>
            <?php endif; ?>

            <div class="dashboard-card">
                <div class="card-header">
                    <h2>Ürün Bilgileri</h2>
                </div>
                <div class="card-content">
                    <?php if (!$error && isset($productData)): ?>
                        <form method="POST" class="product-form" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="product_name">Ürün Adı *</label>
                                    <input type="text" id="product_name" name="product_name" 
                                           class="form-control" value="<?= htmlspecialchars($productData['product_name'] ?? '') ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="product_code">Ürün Kodu</label>
                                    <input type="text" id="product_code" name="product_code" 
                                           class="form-control" value="<?= htmlspecialchars($productData['product_code'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="barcode">Barkod</label>
                                    <input type="text" id="barcode" name="barcode" 
                                           class="form-control" value="<?= htmlspecialchars($productData['barcode'] ?? '') ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="category">Kategori *</label>
                                    <select id="category" name="category" class="form-control" required>
                                        <option value="">Kategori Seçin</option>
                                        <option value="food" <?= ($productData['category'] ?? '') === 'food' ? 'selected' : '' ?>>Gıda</option>
                                        <option value="clothing" <?= ($productData['category'] ?? '') === 'clothing' ? 'selected' : '' ?>>Giyim</option>
                                        <option value="electronics" <?= ($productData['category'] ?? '') === 'electronics' ? 'selected' : '' ?>>Elektronik</option>
                                        <option value="cosmetics" <?= ($productData['category'] ?? '') === 'cosmetics' ? 'selected' : '' ?>>Kozmetik</option>
                                        <option value="furniture" <?= ($productData['category'] ?? '') === 'furniture' ? 'selected' : '' ?>>Mobilya</option>
                                        <option value="other" <?= ($productData['category'] ?? '') === 'other' ? 'selected' : '' ?>>Diğer</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="subcategory">Alt Kategori</label>
                                    <input type="text" id="subcategory" name="subcategory" 
                                           class="form-control" value="<?= htmlspecialchars($productData['subcategory'] ?? '') ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="brand">Marka</label>
                                    <input type="text" id="brand" name="brand" 
                                           class="form-control" value="<?= htmlspecialchars($productData['brand'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Açıklama</label>
                                <textarea id="description" name="description" 
                                          class="form-control" rows="4"><?= htmlspecialchars($productData['description'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="weight">Ağırlık (kg)</label>
                                    <input type="number" id="weight" name="weight" 
                                           class="form-control" step="0.001" value="<?= htmlspecialchars($productData['weight'] ?? '') ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="volume">Hacim (litre)</label>
                                    <input type="number" id="volume" name="volume" 
                                           class="form-control" step="0.001" value="<?= htmlspecialchars($productData['volume'] ?? '') ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="shelf_life">Raf Ömrü (gün)</label>
                                    <input type="number" id="shelf_life" name="shelf_life" 
                                           class="form-control" value="<?= htmlspecialchars($productData['shelf_life'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="packaging_type">Ambalaj Türü</label>
                                    <input type="text" id="packaging_type" name="packaging_type" 
                                           class="form-control" value="<?= htmlspecialchars($productData['packaging_type'] ?? '') ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="origin_country">Menşei Ülke</label>
                                    <input type="text" id="origin_country" name="origin_country" 
                                           class="form-control" value="<?= htmlspecialchars($productData['origin_country'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="origin_region">Menşei Bölge</label>
                                    <input type="text" id="origin_region" name="origin_region" 
                                           class="form-control" value="<?= htmlspecialchars($productData['origin_region'] ?? '') ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="harvest_season">Hasat Sezonu</label>
                                    <input type="text" id="harvest_season" name="harvest_season" 
                                           class="form-control" value="<?= htmlspecialchars($productData['harvest_season'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="status">Durum</label>
                                <select id="status" name="status" class="form-control">
                                    <option value="active" <?= ($productData['status'] ?? '') === 'active' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="inactive" <?= ($productData['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Pasif</option>
                                    <option value="discontinued" <?= ($productData['status'] ?? '') === 'discontinued' ? 'selected' : '' ?>>Üretim Durduruldu</option>
                                </select>
                            </div>
                            
                            <!-- Existing Images -->
                            <?php if (!empty($productData['product_images'])): ?>
                                <div class="form-group">
                                    <label>Mevcut Resimler</label>
                                    <div class="image-preview-container">
                                        <?php foreach ($productData['product_images'] as $image): ?>
                                            <div class="image-preview-item">
                                                <img src="<?= htmlspecialchars($image['url']) ?>" alt="Ürün Resmi" style="width: 100px; height: 100px; object-fit: cover;">
                                                <div class="image-preview-actions">
                                                    <label>
                                                        <input type="checkbox" name="remove_images[]" value="<?= htmlspecialchars($image['filename']) ?>">
                                                        Resmi Kaldır
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- New Image Upload -->
                            <div class="form-group">
                                <label for="product_images">Yeni Resim Ekle</label>
                                <input type="file" id="product_images" name="product_images[]" 
                                       class="form-control" multiple accept="image/*">
                                <small class="form-text">Birden fazla resim seçebilirsiniz (toplamda en fazla 5 adet)</small>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">💾 Ürünü Güncelle</button>
                                <a href="/Küresel/?page=product&action=list" class="btn btn-outline">İptal</a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include FRONTEND_DIR . '/components/footer.php'; ?>
    
    <script src="/Küresel/assets/js/main.js"></script>
</body>
</html>