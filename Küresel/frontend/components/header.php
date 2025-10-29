<?php
$auth = Auth::getInstance();
$currentUser = $auth->getCurrentUser();
?>

<header class="header">
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <a href="/Küresel/">
                    <img src="assets/images/logo.svg" alt="Küresel Etki Zinciri" class="logo">
                    <span class="brand-name">Küresel Etki Zinciri</span>
                </a>
            </div>
            
            <div class="navbar-menu">
                <div class="navbar-nav">
                    <!-- For consumers, home should go to consumer dashboard -->
                    <?php if ($auth->isLoggedIn() && $currentUser->isConsumer()): ?>
                        <a href="/Küresel/consumer" class="nav-link">Anasayfa</a>
                    <?php else: ?>
                        <a href="/Küresel/" class="nav-link">Anasayfa</a>
                    <?php endif; ?>
                    <a href="/Küresel/?page=product" class="nav-link">Ürün Tarama</a>
                    <a href="/Küresel/?page=about" class="nav-link">Hakkımızda</a>
                    <a href="/Küresel/?page=contact" class="nav-link">İletişim</a>
                </div>
                
                <div class="navbar-actions">
                    <?php if ($auth->isLoggedIn()): ?>
                        <div class="user-menu">
                            <button class="user-menu-toggle" onclick="toggleUserMenu()">
                                <span class="user-name"><?= htmlspecialchars($currentUser->getFullName()) ?></span>
                                <span class="user-type badge badge-<?= $currentUser->getUserType() ?>">
                                    <?= ucfirst($currentUser->getUserType()) ?>
                                </span>
                            </button>
                            <div class="user-menu-dropdown">
                                <?php if ($currentUser->isAdmin()): ?>
                                    <a href="/Küresel/?page=admin" class="dropdown-item">Admin Panel</a>
                                <?php elseif ($currentUser->isCompany()): ?>
                                    <a href="/Küresel/?page=company" class="dropdown-item">Şirket Paneli</a>
                                <?php elseif ($currentUser->isValidator()): ?>
                                    <a href="/Küresel/?page=validator" class="dropdown-item">Doğrulayıcı Paneli</a>
                                <?php elseif ($currentUser->isConsumer()): ?>
                                    <a href="/Küresel/?page=consumer" class="dropdown-item">Tüketici Paneli</a>
                                <?php endif; ?>
                                <a href="/Küresel/?page=profile" class="dropdown-item">Profil</a>
                                <a href="/Küresel/?page=settings" class="dropdown-item">Ayarlar</a>
                                <hr class="dropdown-divider">
                                <button onclick="logout()" class="dropdown-item logout-btn">Çıkış Yap</button>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/Küresel/?page=login" class="btn btn-outline btn-sm">Giriş Yap</a>
                        <a href="/Küresel/?page=register" class="btn btn-primary btn-sm">Kayıt Ol</a>
                    <?php endif; ?>
                </div>
            </div>
            
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>
</header>

<script>
function toggleUserMenu() {
    const menu = document.querySelector('.user-menu-dropdown');
    menu.classList.toggle('show');
}

function toggleMobileMenu() {
    const navbar = document.querySelector('.navbar-menu');
    navbar.classList.toggle('mobile-show');
}

function logout() {
    if (confirm('Çıkış yapmak istediğinizden emin misiniz?')) {
        fetch('/Küresel/api/v1/auth/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '<?= $auth->generateCSRFToken() ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/Küresel/?page=login';
            } else {
                alert('Çıkış yapılırken hata oluştu');
                window.location.href = '/Küresel/?page=login';
            }
        })
        .catch(error => {
            console.error('Logout error:', error);
            // Force redirect to login on error
            window.location.href = '/Küresel/?page=login';
        });
    }
}

// Close user menu when clicking outside
document.addEventListener('click', function(event) {
    const userMenu = document.querySelector('.user-menu');
    if (userMenu) {
        const dropdown = userMenu.querySelector('.user-menu-dropdown');
        if (dropdown && !userMenu.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    }
});
</script>