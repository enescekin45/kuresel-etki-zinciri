<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sayfa Bulunamadı - Küresel Etki Zinciri</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include FRONTEND_DIR . '/components/header.php'; ?>
    
    <main class="error-page">
        <div class="container">
            <div class="error-content">
                <div class="error-icon">404</div>
                <h1>Sayfa Bulunamadı</h1>
                <p>Aradığınız sayfa mevcut değil veya taşınmış olabilir.</p>
                <div class="error-actions">
                    <a href="/Küresel/" class="btn btn-primary">Anasayfaya Dön</a>
                    <a href="/Küresel/?pages=product" class="btn btn-outline">Ürün Tarama</a>
                </div>
            </div>
        </div>
    </main>

    <?php include FRONTEND_DIR . '/components/footer.php'; ?>
</body>
</html>