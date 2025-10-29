// QR Code Scanner JavaScript

class QRScanner {
    constructor() {
        this.html5QrCode = null;
        this.isScanning = false;
        this.config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            rememberLastUsedCamera: true
        };
        
        this.initializeScanner();
    }
    
    initializeScanner() {
        const startBtn = document.getElementById('start-scan');
        const stopBtn = document.getElementById('stop-scan');
        
        if (startBtn) {
            startBtn.addEventListener('click', () => this.startScanning());
        }
        
        if (stopBtn) {
            stopBtn.addEventListener('click', () => this.stopScanning());
        }
        
        // Initialize the QR code reader
        this.html5QrCode = new Html5Qrcode("qr-reader");
    }
    
    async startScanning() {
        try {
            const startBtn = document.getElementById('start-scan');
            const stopBtn = document.getElementById('stop-scan');
            
            if (startBtn) startBtn.style.display = 'none';
            if (stopBtn) stopBtn.style.display = 'inline-block';
            
            // Check for camera permissions
            const cameras = await Html5Qrcode.getCameras();
            
            if (cameras && cameras.length) {
                // Use back camera if available
                const cameraId = cameras.length > 1 ? cameras[1].id : cameras[0].id;
                
                await this.html5QrCode.start(
                    cameraId,
                    this.config,
                    (decodedText, decodedResult) => {
                        this.onScanSuccess(decodedText, decodedResult);
                    },
                    (error) => {
                        // Handle scan errors silently
                        // console.warn(`QR Code scan error: ${error}`);
                    }
                );
                
                this.isScanning = true;
                this.showScannerMessage('Kamera aktif - QR kodu taramaya hazır', 'success');
                
            } else {
                throw new Error('Kamera bulunamadı');
            }
            
        } catch (error) {
            console.error('QR Scanner error:', error);
            this.showScannerMessage('Kamera başlatılamadı: ' + error.message, 'error');
            this.resetScannerButtons();
        }
    }
    
    async stopScanning() {
        try {
            if (this.html5QrCode && this.isScanning) {
                await this.html5QrCode.stop();
                this.isScanning = false;
                this.showScannerMessage('Kamera durduruldu', 'info');
            }
        } catch (error) {
            console.error('Error stopping scanner:', error);
        }
        
        this.resetScannerButtons();
    }
    
    resetScannerButtons() {
        const startBtn = document.getElementById('start-scan');
        const stopBtn = document.getElementById('stop-scan');
        
        if (startBtn) startBtn.style.display = 'inline-block';
        if (stopBtn) stopBtn.style.display = 'none';
    }
    
    onScanSuccess(decodedText, decodedResult) {
        console.log('QR Code scanned:', decodedText);
        
        // Stop scanning
        this.stopScanning();
        
        // Process the scanned QR code
        this.processQRCode(decodedText);
    }
    
    async processQRCode(qrData) {
        try {
            this.showScannerMessage('QR kod işleniyor...', 'info');
            
            // Send to backend for processing
            const response = await fetch('/Küresel/api/qr/scan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ qr_data: qrData })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.handleScanResult(result.data);
            } else {
                this.showScannerMessage('QR kod işlenemedi: ' + result.message, 'error');
            }
            
        } catch (error) {
            console.error('QR processing error:', error);
            this.showScannerMessage('QR kod işlenirken hata oluştu', 'error');
        }
    }
    
    handleScanResult(data) {
        if (data.type === 'product') {
            // Redirect to product page
            if (data.product) {
                window.location.href = `/Küresel/?page=product&uuid=${data.product.uuid}`;
            } else {
                this.showScannerMessage('Ürün bulunamadı', 'error');
            }
        } else if (data.type === 'search_results') {
            // Show search results
            this.displaySearchResults(data.products);
        } else {
            this.showScannerMessage('Bilinmeyen QR kod türü', 'error');
        }
    }
    
    displaySearchResults(products) {
        if (products.length === 1) {
            // Single product found, redirect
            window.location.href = `/Küresel/?page=product&uuid=${products[0].uuid}`;
        } else if (products.length > 1) {
            // Multiple products found, show selection
            this.showProductSelection(products);
        } else {
            this.showScannerMessage('QR kod için ürün bulunamadı', 'error');
        }
    }
    
    showProductSelection(products) {
        const modal = document.createElement('div');
        modal.className = 'qr-results-modal';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Bulunan Ürünler</h3>
                    <button class="modal-close" onclick="this.parentElement.parentElement.parentElement.remove()">&times;</button>
                </div>
                <div class="modal-body">
                    <p>QR kod için birden fazla ürün bulundu. Görüntülemek istediğinizi seçin:</p>
                    <div class="product-list">
                        ${products.map(product => `
                            <div class="product-item" onclick="window.location.href='/Küresel/?page=product&uuid=${product.uuid}'">
                                <h4>${product.product_name}</h4>
                                <p class="product-code">${product.product_code}</p>
                                <p class="company-name">${product.company_name}</p>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }
    
    showScannerMessage(message, type) {
        const messageContainer = document.querySelector('.qr-scanner-info');
        if (messageContainer) {
            messageContainer.innerHTML = `<p class="scanner-message scanner-${type}">${message}</p>`;
            
            // Auto-hide success/info messages
            if (type === 'success' || type === 'info') {
                setTimeout(() => {
                    messageContainer.innerHTML = `
                        <p>📱 QR kodu kameranızın görüş alanına getirin</p>
                        <p>🔒 Kamera erişimi yalnızca tarama için kullanılır</p>
                    `;
                }, 3000);
            }
        }
    }
    
    // Check if device has camera
    async hasCameraSupport() {
        try {
            const cameras = await Html5Qrcode.getCameras();
            return cameras && cameras.length > 0;
        } catch (error) {
            return false;
        }
    }
    
    // Get camera permissions
    async requestCameraPermission() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            // Stop the stream immediately as we only needed permission
            stream.getTracks().forEach(track => track.stop());
            return true;
        } catch (error) {
            console.error('Camera permission denied:', error);
            return false;
        }
    }
}

// Initialize scanner when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if QR reader element exists
    if (document.getElementById('qr-reader')) {
        window.qrScanner = new QRScanner();
        
        // Check camera support
        window.qrScanner.hasCameraSupport().then(hasCamera => {
            if (!hasCamera) {
                document.querySelector('.qr-scanner-info').innerHTML = 
                    '<p class="scanner-message scanner-error">Bu cihazda kamera bulunamadı</p>';
            }
        });
    }
});

// Make sure the scanner is properly initialized when switching tabs
function switchTab(tabName) {
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    
    // Add active class to selected tab
    document.querySelector(`[onclick="switchTab('${tabName}')"]`).classList.add('active');
    document.getElementById(`${tabName}-tab`).classList.add('active');
    
    // If switching to QR tab, initialize scanner if needed
    if (tabName === 'qr' && !window.qrScanner) {
        window.qrScanner = new QRScanner();
    }
}