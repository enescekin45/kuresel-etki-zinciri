<!DOCTYPE html>
<html>
<head>
    <title>Test Product Fix</title>
</head>
<body>
    <h1>Testing Product Image Display Fix</h1>
    <div id="loading" style="display:none;">Loading...</div>
    <div id="product-content" style="display:none;"></div>
    <div id="error-message" style="display:none; color:red;"></div>
    
    <script>
        function loadProductDetails(productId, productUuid) {
            // Use the correct API endpoint
            const endpoint = productUuid ? 
                `/Küresel/api/v1/products/get.php?uuid=${productUuid}` : 
                `/Küresel/api/v1/products/get.php?id=${productId}`;
            
            // Show loading spinner
            document.getElementById('loading').style.display = 'block';
            document.getElementById('product-content').style.display = 'none';
            document.getElementById('error-message').style.display = 'none';
            
            fetch(endpoint)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('loading').style.display = 'none';
                    
                    if (data.success) {
                        displayProductDetails(data.data);
                    } else {
                        showError(data.message || 'Ürün bilgileri yüklenirken hata oluştu');
                    }
                })
                .catch(error => {
                    console.error('Error loading product:', error);
                    document.getElementById('loading').style.display = 'none';
                    showError('Ürün bilgileri yüklenirken hata oluştu: ' + error.message);
                });
        }
        
        function displayProductDetails(product) {
            const content = document.getElementById('product-content');
            
            // Format product images - FIXED: Properly handle image paths
            let productImages = [];
            if (product.product_images && Array.isArray(product.product_images)) {
                productImages = product.product_images;
            } else if (typeof product.product_images === 'string') {
                try {
                    productImages = JSON.parse(product.product_images);
                } catch (e) {
                    productImages = [];
                }
            }
            
            // FIXED: Ensure proper image path handling
            let mainImage = '/Küresel/assets/images/product-placeholder.jpg';
            if (productImages.length > 0) {
                // Use the image path directly as it's already correctly formatted
                mainImage = productImages[0];
            }
            
            content.innerHTML = `
                <h2>${product.product_name}</h2>
                <div>
                    <img src="${mainImage}" alt="${product.product_name}" style="max-width: 300px; border: 1px solid #ccc;" onerror="this.src='/Küresel/assets/images/product-placeholder.jpg'">
                </div>
                <p>ID: ${product.id}</p>
                <p>UUID: ${product.uuid}</p>
                <p>Code: ${product.product_code}</p>
                <p>Category: ${product.category}</p>
                <p>Description: ${product.description || 'No description'}</p>
                <p>Image path: ${mainImage}</p>
            `;
            
            content.style.display = 'block';
        }
        
        function showError(message) {
            document.getElementById('error-message').style.display = 'block';
            document.getElementById('error-message').textContent = message;
        }
        
        // Test with the Organik Zeytinyağı product (ID 2)
        loadProductDetails(2, null);
    </script>
</body>
</html>