<?php
// Create proper placeholder images for all products

// Define the products and their image names
$products = [
    ['name' => 'Organik Zeytinyağı', 'image_name' => 'organik-zeytinyagi'],
    ['name' => 'Bal', 'image_name' => 'bal'],
    ['name' => 'Ton Balığı Konservesi', 'image_name' => 'ton-baligi'],
    ['name' => 'Organik Pamuk Tişört', 'image_name' => 'tisort'],
    ['name' => 'Kot Pantolon', 'image_name' => 'kot-pantolon'],
    ['name' => 'Akıllı Telefon', 'image_name' => 'akilli-telefon'],
    ['name' => 'Dizüstü Bilgisayar', 'image_name' => 'dizustu-bilgisayar'],
    ['name' => 'Doğal Şampuan', 'image_name' => 'dogal-sampuan'],
    ['name' => 'Organik Krem', 'image_name' => 'organik-krem']
];

// SVG template with product-specific text
$svgTemplate = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="300" xmlns="http://www.w3.org/2000/svg">
  <rect width="100%" height="100%" fill="#f0f0f0"/>
  <rect x="20" y="20" width="260" height="260" fill="#e0e0e0" stroke="#ccc" stroke-width="2"/>
  <text x="150" y="140" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="#333">Product Image</text>
  <text x="150" y="170" font-family="Arial, sans-serif" font-size="14" text-anchor="middle" fill="#666">%%PRODUCT_NAME%%</text>
  <text x="150" y="200" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#999">%%IMAGE_NAME%%</text>
</svg>';

$imagesDir = __DIR__ . '/assets/images/products/';

// Create SVG images for each product
foreach ($products as $product) {
    $imageName = $product['image_name'];
    $svgContent = str_replace(
        ['%%PRODUCT_NAME%%', '%%IMAGE_NAME%%'],
        [$product['name'], $imageName],
        $svgTemplate
    );
    
    // Save SVG file
    file_put_contents($imagesDir . $imageName . '.svg', $svgContent);
    
    // Also create a simple JPG placeholder (just a small text file for now)
    $jpgContent = "Product: " . $product['name'] . "\nImage: " . $imageName;
    file_put_contents($imagesDir . $imageName . '.jpg', $jpgContent);
    
    echo "Created images for: " . $product['name'] . "\n";
}

echo "All product images created successfully!\n";
?>