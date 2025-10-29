<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Küresel Etki Zinciri</h3>
                <p>Şeffaflık ve hesap verebilirlik temelli sürdürülebilir bir tedarik zinciri ekosistemi oluşturuyoruz.</p>
                <div class="social-links">
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                    <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
                </div>
            </div>
            
            <div class="footer-section">
                <h4>Platform</h4>
                <ul>
                    <li><a href="/Küresel/?page=product">Ürün Tarama</a></li>
                    <li><a href="/Küresel/?page=company">Şirket Kaydı</a></li>
                    <li><a href="/Küresel/?page=validator">Doğrulayıcı Ol</a></li>
                    <li><a href="/api-docs">API Dokümantasyonu</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Şirket</h4>
                <ul>
                    <li><a href="/Küresel/?page=about">Hakkımızda</a></li>
                    <li><a href="/Küresel/?page=team">Ekibimiz</a></li>
                    <li><a href="/Küresel/?page=careers">Kariyer</a></li>
                    <li><a href="/Küresel/?page=press">Basın</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Destek</h4>
                <ul>
                    <li><a href="/Küresel/?page=help">Yardım Merkezi</a></li>
                    <li><a href="/Küresel/?page=contact">İletişim</a></li>
                    <li><a href="/Küresel/?page=privacy">Gizlilik Politikası</a></li>
                    <li><a href="/Küresel/?page=terms">Kullanım Şartları</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Bülten</h4>
                <p>Platformdaki yeniliklerden haberdar olmak için bültenimize abone olun.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="E-posta adresiniz" required>
                    <button type="submit" class="btn btn-primary">Abone Ol</button>
                </form>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p>&copy; 2025 Küresel Etki Zinciri. Tüm hakları saklıdır.</p>
                <div class="footer-links">
                    <a href="/Küresel/?page=privacy">Gizlilik</a>
                    <a href="/Küresel/?page=terms">Şartlar</a>
                    <a href="/Küresel/?page=cookies">Çerezler</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
// Newsletter subscription
document.querySelector('.newsletter-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const email = this.querySelector('input[type="email"]').value;
    
    // Implement newsletter subscription
    fetch('api/newsletter/subscribe', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Bültene başarıyla abone oldunuz!');
            this.reset();
        } else {
            alert('Abonelik sırasında hata oluştu: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Newsletter subscription error:', error);
        alert('Abonelik sırasında hata oluştu');
    });
});
</script>