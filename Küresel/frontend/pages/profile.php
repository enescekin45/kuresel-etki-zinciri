<?php
// Check if user is logged in
$auth = Auth::getInstance();
if (!$auth->isLoggedIn()) {
    header('Location: /Küresel/?page=login');
    exit;
}

$currentUser = $auth->getCurrentUser();
include FRONTEND_DIR . '/components/header.php';
?>
<link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
<link rel="stylesheet" href="assets/css/dashboard.css?v=<?= time() ?>">

<main class="page-content dashboard" data-version="<?= time() ?>">
    <div class="container">
        <div class="dashboard-header">
            <h1>👤 Kullanıcı Profili</h1>
            <p class="subtitle">Hesap bilgilerinizi yönetin ve profilinizi güncelleyin</p>
        </div>
        
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
                                        <?php if ($currentUser->getProfileImage()): ?>
                                            <img src="<?= htmlspecialchars($currentUser->getProfileImage()) ?>" alt="Profil Resmi" class="profile-image">
                                        <?php else: ?>
                                            <span class="avatar-initials"><?= strtoupper(substr($currentUser->getFirstName(), 0, 1) . substr($currentUser->getLastName(), 0, 1)) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="profile-info">
                                    <h2><?= htmlspecialchars($currentUser->getFullName()) ?></h2>
                                    <p class="profile-email">📧 <?= htmlspecialchars($currentUser->getEmail()) ?></p>
                                    <p class="profile-type">
                                        <span class="badge badge-<?= $currentUser->getUserType() ?>">
                                            <?= ucfirst($currentUser->getUserType()) ?>
                                        </span>
                                    </p>
                                    <p class="profile-id">ID: <?= htmlspecialchars($currentUser->getId()) ?></p>
                                    <div class="profile-stats">
                                        <div class="stat-item">
                                            <span class="stat-label">📅 Kayıt Tarihi</span>
                                            <span class="stat-value"><?= $currentUser->getCreatedAt() ? date('d.m.Y', strtotime($currentUser->getCreatedAt())) : '-' ?></span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-label">🕒 Son Giriş</span>
                                            <span class="stat-value"><?= $currentUser->getLastLogin() ? date('d.m.Y H:i', strtotime($currentUser->getLastLogin())) : '-' ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="profile-details">
                                <h3>📋 Kişisel Bilgiler</h3>
                                <div class="profile-field">
                                    <label>👤 Ad:</label>
                                    <span><?= htmlspecialchars($currentUser->getFirstName()) ?></span>
                                </div>
                                
                                <div class="profile-field">
                                    <label>👤 Soyad:</label>
                                    <span><?= htmlspecialchars($currentUser->getLastName()) ?></span>
                                </div>
                                
                                <div class="profile-field">
                                    <label>📧 E-posta:</label>
                                    <span><?= htmlspecialchars($currentUser->getEmail()) ?></span>
                                </div>
                                
                                <div class="profile-field">
                                    <label>📱 Telefon:</label>
                                    <span><?= htmlspecialchars($currentUser->getPhone() ?? '-') ?></span>
                                </div>
                            </div>
                            
                            <div class="profile-actions">
                                <a href="/Küresel/?page=settings" class="btn btn-primary">⚙️ Bilgileri Düzenle</a>
                                <a href="/Küresel/?page=change-password" class="btn btn-outline">🔑 Şifre Değiştir</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Summary -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>📊 Hesap Özeti</h2>
                </div>
                <div class="card-content">
                    <div class="summary-item">
                        <span class="summary-label">Hesap Durumu</span>
                        <span class="summary-value badge badge-success">Aktif</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Üyelik Türü</span>
                        <span class="summary-value badge badge-<?= $currentUser->getUserType() ?>"><?= ucfirst($currentUser->getUserType()) ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Kayıt Tarihi</span>
                        <span class="summary-value"><?= $currentUser->getCreatedAt() ? date('d.m.Y', strtotime($currentUser->getCreatedAt())) : '-' ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Toplam Giriş</span>
                        <span class="summary-value">12</span>
                    </div>
                </div>
            </div>

            <!-- Security -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>🔒 Güvenlik</h2>
                </div>
                <div class="card-content">
                    <div class="security-item">
                        <span class="security-label">Son Şifre Değişikliği</span>
                        <span class="security-value">30 gün önce</span>
                    </div>
                    <div class="security-item">
                        <span class="security-label">Bağlı Cihazlar</span>
                        <span class="security-value">2 cihaz</span>
                    </div>
                    <div class="security-item">
                        <span class="security-label">İki Faktörlü Kimlik Doğrulama</span>
                        <span class="security-value badge badge-warning">Devre Dışı</span>
                    </div>
                    <div class="security-item">
                        <span class="security-label">Hesap Güvenlik Puanı</span>
                        <span class="security-value">
                            <div class="security-score">
                                <div class="score-bar">
                                    <div class="score-fill" style="width: 85%"></div>
                                </div>
                                <span class="score-text">85/100</span>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="dashboard-card full-width">
            <div class="card-header">
                <h2>🕒 Son Aktiviteler</h2>
            </div>
            <div class="card-content">
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon">🔒</div>
                        <div class="activity-content">
                            <h4>Hesabınıza giriş yapıldı</h4>
                            <p class="activity-time">2 saat önce - Chrome, Windows</p>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">⚙️</div>
                        <div class="activity-content">
                            <h4>Profil bilgileri güncellendi</h4>
                            <p class="activity-time">1 gün önce</p>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">🔑</div>
                        <div class="activity-content">
                            <h4>Şifre değiştirildi</h4>
                            <p class="activity-time">1 hafta önce</p>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon">📱</div>
                        <div class="activity-content">
                            <h4>Yeni cihaz bağlandı</h4>
                            <p class="activity-time">2 hafta önce - Firefox, macOS</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

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
}

.profile-actions {
    display: flex;
    gap: 1rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

.summary-item, .security-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.summary-item:last-child, .security-item:last-child {
    border-bottom: none;
}

.summary-label, .security-label {
    font-weight: 500;
    color: #4a5568;
}

.summary-value, .security-value {
    font-weight: 500;
    color: #2d3748;
}

.security-score {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.score-bar {
    width: 100px;
    height: 8px;
    background: #e2e8f0;
    border-radius: 4px;
    overflow: hidden;
}

.score-fill {
    height: 100%;
    background: linear-gradient(90deg, #48bb78, #38a169);
    border-radius: 4px;
}

.score-text {
    font-size: 0.8rem;
    font-weight: 600;
    color: #2d3748;
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.activity-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    border-radius: 8px;
    transition: background-color 0.2s;
}

.activity-item:hover {
    background: #f8fafc;
}

.activity-icon {
    font-size: 1.5rem;
    flex-shrink: 0;
}

.activity-content h4 {
    margin: 0 0 0.25rem 0;
    color: #2d3748;
    font-size: 1rem;
}

.activity-time {
    margin: 0;
    font-size: 0.875rem;
    color: #718096;
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
    
    .profile-actions {
        flex-direction: column;
    }
    
    .activity-item {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<script>
// Show notification function
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(el => el.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}
</script>

<?php include FRONTEND_DIR . '/components/footer.php'; ?>