<?php
require_once CLASSES_DIR . '/Auth.php';

$auth = Auth::getInstance();

// Redirect to login if not authenticated
if (!$auth->isLoggedIn()) {
    header('Location: /Küresel/?page=login');
    exit;
}

$currentUser = $auth->getCurrentUser();

// Only allow admin users to access this page
if (!$currentUser->isAdmin()) {
    header('Location: /Küresel/');
    exit;
}

// Get stats data server-side for initial load
try {
    $db = Database::getInstance();
    
    // Get total users count
    $sql = "SELECT COUNT(*) as total FROM users";
    $result = $db->fetchRow($sql);
    $totalUsers = $result['total'];
    
    // Get total companies count
    $sql = "SELECT COUNT(*) as total FROM companies";
    $result = $db->fetchRow($sql);
    $totalCompanies = $result['total'];
    
    // Get total validators count
    $sql = "SELECT COUNT(*) as total FROM validators";
    $result = $db->fetchRow($sql);
    $totalValidators = $result['total'];
    
    // Get total products count
    $sql = "SELECT COUNT(*) as total FROM products";
    $result = $db->fetchRow($sql);
    $totalProducts = $result['total'];
    
    // Get recent users
    $sql = "SELECT u.id, u.first_name, u.last_name, u.email, u.user_type, u.status, u.created_at 
            FROM users u 
            ORDER BY u.created_at DESC 
            LIMIT 10";
    $recentUsers = $db->fetchAll($sql);
    
} catch (Exception $e) {
    $totalUsers = 0;
    $totalCompanies = 0;
    $totalValidators = 0;
    $totalProducts = 0;
    $recentUsers = [];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli - Küresel Etki Zinciri</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="<?= $auth->generateCSRFToken() ?>">
</head>
<body>
    <?php include FRONTEND_DIR . '/components/header.php'; ?>
    
    <main class="dashboard admin-dashboard">
        <div class="container">
            <div class="dashboard-header">
                <h1>Admin Paneli</h1>
                <p class="subtitle">Küresel Etki Zinciri - Sistem Yönetimi</p>
            </div>

            <!-- System Statistics with Charts -->
            <div class="dashboard-grid">
                <!-- System Statistics -->
                <div class="dashboard-card full-width">
                    <div class="card-header">
                        <h2>Sistem İstatistikleri</h2>
                    </div>
                    <div class="card-content">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number" id="users-count"><?= $totalUsers ?></div>
                                <div class="stat-label">Toplam Kullanıcı</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number" id="companies-count"><?= $totalCompanies ?></div>
                                <div class="stat-label">Şirketler</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number" id="validators-count"><?= $totalValidators ?></div>
                                <div class="stat-label">Doğrulayıcılar</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number" id="products-count"><?= $totalProducts ?></div>
                                <div class="stat-label">Ürünler</div>
                            </div>
                        </div>
                        
                        <!-- Chart Tabs -->
                        <div class="chart-tabs mt-4">
                            <div class="tabs">
                                <button class="tab-button active" data-tab="registration">Kayıt İstatistikleri</button>
                                <button class="tab-button" data-tab="company">Şirket Türleri</button>
                                <button class="tab-button" data-tab="product">Ürün Kategorileri</button>
                                <button class="tab-button" data-tab="validation">Doğrulama Sonuçları</button>
                            </div>
                            
                            <!-- Chart Containers -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="registration-chart">
                                    <div class="chart-container">
                                        <div class="chart-wrapper">
                                            <canvas id="userRegistrationChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="company-chart">
                                    <div class="chart-container">
                                        <div class="chart-wrapper">
                                            <canvas id="companyTypeChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="product-chart">
                                    <div class="chart-container">
                                        <div class="chart-wrapper">
                                            <canvas id="productCategoryChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="validation-chart">
                                    <div class="chart-container">
                                        <div class="chart-wrapper">
                                            <canvas id="validationResultChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="dashboard-card full-width">
                    <div class="card-header">
                        <h2>Son Kayıt Olan Kullanıcılar</h2>
                        <a href="/Küresel/?page=admin&section=users" class="btn btn-outline btn-sm">Tümünü Gör</a>
                    </div>
                    <div class="card-content">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Kullanıcı</th>
                                        <th>Email</th>
                                        <th>Tür</th>
                                        <th>Durum</th>
                                        <th>Kayıt Tarihi</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($recentUsers)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Kayıtlı kullanıcı bulunamadı</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($recentUsers as $user): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                                                <td><?= htmlspecialchars($user['email']) ?></td>
                                                <td>
                                                    <span class="badge badge-<?= $user['user_type'] ?>">
                                                        <?= ucfirst($user['user_type']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-<?= $user['status'] ?>">
                                                        <?= ucfirst($user['status']) ?>
                                                    </span>
                                                </td>
                                                <td><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline view-user-details" data-user-id="<?= $user['id'] ?>">Detaylar</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>Bekleyen Onaylar</h2>
                    </div>
                    <div class="card-content">
                        <div class="approval-list">
                            <div class="approval-item">
                                <span class="approval-type">Şirket Kayıtları</span>
                                <span class="approval-count badge badge-warning">3</span>
                            </div>
                            <div class="approval-item">
                                <span class="approval-type">Doğrulayıcı Başvuruları</span>
                                <span class="approval-count badge badge-info">2</span>
                            </div>
                            <div class="approval-item">
                                <span class="approval-type">Ürün Onayları</span>
                                <span class="approval-count badge badge-primary">5</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Health -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h2>Sistem Durumu</h2>
                    </div>
                    <div class="card-content">
                        <div class="health-indicators">
                            <div class="health-item">
                                <span class="health-label">Database</span>
                                <span class="health-status badge badge-success">Aktif</span>
                            </div>
                            <div class="health-item">
                                <span class="health-label">Blockchain</span>
                                <span class="health-status badge badge-success">Bağlı</span>
                            </div>
                            <div class="health-item">
                                <span class="health-label">API</span>
                                <span class="health-status badge badge-success">Çalışıyor</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3>Hızlı İşlemler</h3>
                <div class="actions-grid">
                    <a href="/Küresel/?page=admin&section=users" class="action-btn">
                        <span class="action-icon">👥</span>
                        <span class="action-text">Kullanıcı Yönetimi</span>
                    </a>
                    <a href="/Küresel/?page=admin&section=companies" class="action-btn">
                        <span class="action-icon">🏢</span>
                        <span class="action-text">Şirket Yönetimi</span>
                    </a>
                    <a href="/Küresel/?page=admin&section=validators" class="action-btn">
                        <span class="action-icon">✅</span>
                        <span class="action-text">Doğrulayıcı Yönetimi</span>
                    </a>
                    <a href="/Küresel/?page=admin&section=products" class="action-btn">
                        <span class="action-icon">📦</span>
                        <span class="action-text">Ürün Yönetimi</span>
                    </a>
                    <a href="/Küresel/?page=admin&section=blockchain" class="action-btn">
                        <span class="action-icon">🔗</span>
                        <span class="action-text">Blockchain İzleme</span>
                    </a>
                    <a href="/Küresel/?page=admin&section=reports" class="action-btn">
                        <span class="action-icon">📊</span>
                        <span class="action-text">Raporlar</span>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <?php include FRONTEND_DIR . '/components/footer.php'; ?>
    
    <!-- User Details Modal -->
    <div id="userDetailsModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Kullanıcı Detayları</h3>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <div id="userDetailsContent">
                    <!-- User details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/libs/chart.umd.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
    // Initialize admin dashboard
    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
        
        // Tab switching functionality
        const tabButtons = document.querySelectorAll('.tab-button');
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                switchTab(this.getAttribute('data-tab'));
            });
        });
        
        // User details functionality
        const detailButtons = document.querySelectorAll('.view-user-details');
        detailButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                showUserDetails(userId);
            });
        });
        
        // Close modal functionality
        const closeModal = document.querySelector('.close-modal');
        if (closeModal) {
            closeModal.addEventListener('click', function() {
                document.getElementById('userDetailsModal').style.display = 'none';
            });
        }
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('userDetailsModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
    
    function switchTab(tabId) {
        // Remove active class from all buttons and panes
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
        
        // Add active class to corresponding tab button
        const activeTabButton = document.querySelector(`.tab-button[data-tab="${tabId}"]`);
        if (activeTabButton) {
            activeTabButton.classList.add('active');
        }
        
        // Show corresponding pane
        const activePane = document.getElementById(`${tabId}-chart`);
        if (activePane) {
            activePane.classList.add('active');
        }
    }
    
    function showUserDetails(userId) {
        // Show loading message
        const modal = document.getElementById('userDetailsModal');
        const content = document.getElementById('userDetailsContent');
        content.innerHTML = '<p>Kullanıcı bilgileri yükleniyor...</p>';
        modal.style.display = 'block';
        
        // Fetch user details - Fixed the URL to include the base path
        fetch(`/Küresel/api/admin/users/${userId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Display user details
                    content.innerHTML = `
                        <div class="user-details">
                            <div class="detail-row">
                                <strong>Ad Soyad:</strong>
                                <span>${data.user.first_name} ${data.user.last_name}</span>
                            </div>
                            <div class="detail-row">
                                <strong>Email:</strong>
                                <span>${data.user.email}</span>
                            </div>
                            <div class="detail-row">
                                <strong>Kullanıcı Türü:</strong>
                                <span>${getUserTypeLabel(data.user.user_type)}</span>
                            </div>
                            <div class="detail-row">
                                <strong>Durum:</strong>
                                <span>${getUserStatusLabel(data.user.status)}</span>
                            </div>
                            <div class="detail-row">
                                <strong>Kayıt Tarihi:</strong>
                                <span>${formatDate(data.user.created_at)}</span>
                            </div>
                            ${data.user.company_name ? `
                            <div class="detail-row">
                                <strong>Şirket:</strong>
                                <span>${data.user.company_name}</span>
                            </div>` : ''}
                            ${data.user.specialization ? `
                            <div class="detail-row">
                                <strong>Uzmanlık:</strong>
                                <span>${data.user.specialization}</span>
                            </div>` : ''}
                        </div>
                    `;
                } else {
                    content.innerHTML = '<p>Kullanıcı bilgileri yüklenirken bir hata oluştu: ' + (data.message || 'Bilinmeyen hata') + '</p>';
                }
            })
            .catch(error => {
                console.error('Error loading user details:', error);
                content.innerHTML = '<p>Kullanıcı bilgileri yüklenirken bir hata oluştu: ' + error.message + '</p>';
            });
    }
    
    function getUserTypeLabel(type) {
        switch(type) {
            case 'admin': return 'Yönetici';
            case 'company': return 'Şirket';
            case 'validator': return 'Doğrulayıcı';
            case 'consumer': return 'Tüketici';
            default: return type;
        }
    }
    
    function getUserStatusLabel(status) {
        switch(status) {
            case 'active': return 'Aktif';
            case 'inactive': return 'Pasif';
            case 'pending': return 'Beklemede';
            default: return status;
        }
    }
    
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('tr-TR') + ' ' + date.toLocaleTimeString('tr-TR');
    }
    
    function initializeCharts() {
        // Fetch data for charts - Fixed the URL to include the base path
        fetch('/Küresel/api/admin/stats/overview')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // User Registration Chart (Line Chart)
                    const registrationCtx = document.getElementById('userRegistrationChart').getContext('2d');
                    new Chart(registrationCtx, {
                        type: 'line',
                        data: {
                            labels: data.data.user_registration.labels,
                            datasets: data.data.user_registration.datasets.map((dataset, index) => {
                                const colors = [
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(255, 205, 86, 1)',
                                    'rgba(75, 192, 192, 1)'
                                ];
                                const bgColors = [
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(255, 205, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)'
                                ];
                                
                                return {
                                    label: dataset.label,
                                    data: dataset.data,
                                    borderColor: colors[index % colors.length],
                                    backgroundColor: bgColors[index % bgColors.length],
                                    tension: 0.1
                                };
                            })
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                    
                    // Company Type Chart (Doughnut Chart)
                    const companyCtx = document.getElementById('companyTypeChart').getContext('2d');
                    new Chart(companyCtx, {
                        type: 'doughnut',
                        data: {
                            labels: data.data.companies_by_type.map(item => item.company_type),
                            datasets: [{
                                data: data.data.companies_by_type.map(item => parseInt(item.count)),
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.7)',
                                    'rgba(255, 99, 132, 0.7)',
                                    'rgba(255, 205, 86, 0.7)',
                                    'rgba(75, 192, 192, 0.7)',
                                    'rgba(153, 102, 255, 0.7)',
                                    'rgba(255, 159, 64, 0.7)'
                                ],
                                borderColor: [
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(255, 205, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
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
                                }
                            }
                        }
                    });
                    
                    // Product Category Chart (Bar Chart)
                    const productCtx = document.getElementById('productCategoryChart').getContext('2d');
                    new Chart(productCtx, {
                        type: 'bar',
                        data: {
                            labels: data.data.products_by_category.map(item => item.category),
                            datasets: [{
                                label: 'Ürün Sayısı',
                                data: data.data.products_by_category.map(item => parseInt(item.count)),
                                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: 'y',
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                    
                    // Validation Result Chart (Pie Chart)
                    const validationCtx = document.getElementById('validationResultChart').getContext('2d');
                    new Chart(validationCtx, {
                        type: 'pie',
                        data: {
                            labels: data.data.validations_by_result.map(item => {
                                switch(item.validation_result) {
                                    case 'approved': return 'Onaylandı';
                                    case 'rejected': return 'Reddedildi';
                                    case 'needs_clarification': return 'Açıklama Gerekli';
                                    default: return item.validation_result;
                                }
                            }),
                            datasets: [{
                                data: data.data.validations_by_result.map(item => parseInt(item.count)),
                                backgroundColor: [
                                    'rgba(75, 192, 192, 0.7)',
                                    'rgba(255, 99, 132, 0.7)',
                                    'rgba(255, 205, 86, 0.7)'
                                ],
                                borderColor: [
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(255, 205, 86, 1)'
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
                                }
                            }
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error loading chart data:', error);
            });
    }
    </script>
</body>
</html>