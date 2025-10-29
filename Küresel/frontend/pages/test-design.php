<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Design Test - Küresel Etki Zinciri</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include FRONTEND_DIR . '/components/header.php'; ?>
    
    <main>
        <section class="page-hero">
            <div class="container">
                <h1>Design Test Page</h1>
                <p class="page-subtitle">This page tests if the design fixes are working correctly</p>
            </div>
        </section>
        
        <div class="container">
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>Test Card 1</h2>
                    </div>
                    <div class="card-content">
                        <p>This is a test card to verify styling is working correctly.</p>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>Test Card 2</h2>
                    </div>
                    <div class="card-content">
                        <p>This is another test card to verify styling is working correctly.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include FRONTEND_DIR . '/components/footer.php'; ?>
</body>
</html>