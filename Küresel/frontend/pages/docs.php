<?php
include FRONTEND_DIR . '/components/header.php';
?>

<main class="page-content">
    <div class="container">
        <div class="page-header">
            <h1>📚 Dokümantasyon</h1>
            <p>Küresel Etki Zinciri platformu dokümantasyonu</p>
        </div>
        
        <div class="docs-content">
            <section class="docs-section">
                <h2>📖 Dokümantasyon Kaynakları</h2>
                <p>Platformumuz için çeşitli dokümantasyon kaynaklarına aşağıdan ulaşabilirsiniz:</p>
                
                <div class="docs-grid">
                    <div class="docs-card">
                        <h3>📚 API Dokümantasyonu</h3>
                        <p>Platform API'sini kullanmak için detaylı dokümantasyon</p>
                        <a href="/Küresel/?page=api-docs" class="btn btn-primary">API Dokümanlarını Görüntüle</a>
                    </div>
                    
                    <div class="docs-card">
                        <h3>📋 Kullanıcı Rehberi</h3>
                        <p>Platformu nasıl kullanacağınız hakkında bilgiler</p>
                        <a href="#" class="btn btn-outline">Kullanıcı Rehberini Görüntüle</a>
                    </div>
                    
                    <div class="docs-card">
                        <h3>⚙️ Geliştirici Dokümantasyonu</h3>
                        <p>Platform üzerinde geliştirme yapmak için teknik dokümantasyon</p>
                        <a href="#" class="btn btn-outline">Geliştirici Dokümanlarını Görüntüle</a>
                    </div>
                </div>
            </section>
            
            <section class="docs-section">
                <h2>❓ Sıkça Sorulan Sorular</h2>
                
                <div class="faq-item">
                    <h3>API erişimi nasıl sağlanır?</h3>
                    <p>API erişimi için profil ayarlarınızdan bir API anahtarı oluşturmanız gerekir. 
                    Oluşturduğunuz anahtarı HTTP Authorization başlığında Bearer token olarak kullanabilirsiniz.</p>
                </div>
                
                <div class="faq-item">
                    <h3>Rate limiting nedir?</h3>
                    <p>API kullanımınızı kontrol altına almak için saatlik 1000 istek limiti uygulanmaktadır. 
                    Bu limiti aşarsanız 429 HTTP kodu ile yanıt alırsınız.</p>
                </div>
                
                <div class="faq-item">
                    <h3>API hataları nasıl işlenir?</h3>
                    <p>Tüm API hataları standart HTTP durum kodları ile döner. 
                    Hata detayları JSON formatında response body içinde bulunur.</p>
                </div>
            </section>
            
            <section class="docs-section">
                <h2>📬 İletişim</h2>
                <p>Dokümantasyon ile ilgili sorularınız için <a href="mailto:docs@kuresaletzinciri.com">docs@kuresaletzinciri.com</a> adresine e-posta gönderin.</p>
                <p>GitHub üzerinden de destek alabilirsiniz: <a href="#">github.com/kuresaletzinciri/docs</a></p>
            </section>
        </div>
    </div>
</main>

<style>
.docs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

.docs-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    transition: all 0.2s;
}

.docs-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}

.docs-card h3 {
    margin-top: 0;
    color: #2d3748;
}

.docs-card p {
    color: #4a5568;
    margin-bottom: 1.5rem;
}

.faq-item {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.faq-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.faq-item h3 {
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.faq-item p {
    color: #4a5568;
    margin: 0;
}
</style>

<?php include FRONTEND_DIR . '/components/footer.php'; ?>