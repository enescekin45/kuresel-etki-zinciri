<?php
// Create proper SVG images with actual visuals for all products

// Define the products and their image names with specific visual elements
$products = [
    [
        'name' => 'Organik Zeytinyağı',
        'image_name' => 'organik-zeytinyagi',
        'category' => 'food',
        'color' => '#228B22'
    ],
    [
        'name' => 'Bal',
        'image_name' => 'bal',
        'category' => 'food',
        'color' => '#D2691E'
    ],
    [
        'name' => 'Ton Balığı Konservesi',
        'image_name' => 'ton-baligi',
        'category' => 'food',
        'color' => '#708090'
    ],
    [
        'name' => 'Organik Pamuk Tişört',
        'image_name' => 'tisort',
        'category' => 'clothing',
        'color' => '#87CEEB'
    ],
    [
        'name' => 'Kot Pantolon',
        'image_name' => 'kot-pantolon',
        'category' => 'clothing',
        'color' => '#4682B4'
    ],
    [
        'name' => 'Akıllı Telefon',
        'image_name' => 'akilli-telefon',
        'category' => 'electronics',
        'color' => '#4169E1'
    ],
    [
        'name' => 'Dizüstü Bilgisayar',
        'image_name' => 'dizustu-bilgisayar',
        'category' => 'electronics',
        'color' => '#696969'
    ],
    [
        'name' => 'Doğal Şampuan',
        'image_name' => 'dogal-sampuan',
        'category' => 'cosmetics',
        'color' => '#9370DB'
    ],
    [
        'name' => 'Organik Krem',
        'image_name' => 'organik-krem',
        'category' => 'cosmetics',
        'color' => '#DDA0DD'
    ]
];

$imagesDir = __DIR__ . '/assets/images/products/';

// SVG templates for different categories
$svgTemplates = [
    'food' => '<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="300" xmlns="http://www.w3.org/2000/svg">
  <rect width="100%" height="100%" fill="#f8f9fa"/>
  <rect x="0" y="0" width="300" height="300" fill="url(#bgGradient)" opacity="0.3"/>
  
  <!-- Background gradient -->
  <defs>
    <linearGradient id="bgGradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#e9ecef;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#dee2e6;stop-opacity:1" />
    </linearGradient>
    <linearGradient id="productGradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:%%COLOR%%;stop-opacity:0.8" />
      <stop offset="100%" style="stop-color:%%DARK_COLOR%%;stop-opacity:0.9" />
    </linearGradient>
  </defs>
  
  <!-- Product visualization -->
  %%PRODUCT_SHAPE%%
  
  <!-- Product label -->
  <rect x="50" y="230" width="200" height="40" rx="5" fill="rgba(255,255,255,0.9)" stroke="#ddd"/>
  <text x="150" y="255" font-family="Arial, sans-serif" font-size="14" font-weight="bold" text-anchor="middle" fill="#333">%%PRODUCT_NAME%%</text>
</svg>',
    
    'electronics' => '<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="300" xmlns="http://www.w3.org/2000/svg">
  <rect width="100%" height="100%" fill="#f8f9fa"/>
  <rect x="0" y="0" width="300" height="300" fill="url(#bgGradient)" opacity="0.3"/>
  
  <!-- Background gradient -->
  <defs>
    <linearGradient id="bgGradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#e9ecef;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#dee2e6;stop-opacity:1" />
    </linearGradient>
    <linearGradient id="productGradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:%%COLOR%%;stop-opacity:0.8" />
      <stop offset="100%" style="stop-color:%%DARK_COLOR%%;stop-opacity:0.9" />
    </linearGradient>
    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
      <feGaussianBlur in="SourceAlpha" stdDeviation="3"/>
      <feOffset dx="2" dy="2" result="offsetblur"/>
      <feFlood flood-color="rgba(0,0,0,0.3)"/>
      <feComposite in2="offsetblur" operator="in"/>
      <feMerge>
        <feMergeNode/>
        <feMergeNode in="SourceGraphic"/>
      </feMerge>
    </filter>
  </defs>
  
  <!-- Product visualization -->
  %%PRODUCT_SHAPE%%
  
  <!-- Product label -->
  <rect x="50" y="230" width="200" height="40" rx="5" fill="rgba(255,255,255,0.9)" stroke="#ddd"/>
  <text x="150" y="255" font-family="Arial, sans-serif" font-size="14" font-weight="bold" text-anchor="middle" fill="#333">%%PRODUCT_NAME%%</text>
</svg>',
    
    'clothing' => '<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="300" xmlns="http://www.w3.org/2000/svg">
  <rect width="100%" height="100%" fill="#f8f9fa"/>
  <rect x="0" y="0" width="300" height="300" fill="url(#bgGradient)" opacity="0.3"/>
  
  <!-- Background gradient -->
  <defs>
    <linearGradient id="bgGradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#e9ecef;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#dee2e6;stop-opacity:1" />
    </linearGradient>
    <linearGradient id="productGradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:%%COLOR%%;stop-opacity:0.8" />
      <stop offset="100%" style="stop-color:%%DARK_COLOR%%;stop-opacity:0.9" />
    </linearGradient>
    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
      <feGaussianBlur in="SourceAlpha" stdDeviation="2"/>
      <feOffset dx="1" dy="1" result="offsetblur"/>
      <feFlood flood-color="rgba(0,0,0,0.2)"/>
      <feComposite in2="offsetblur" operator="in"/>
      <feMerge>
        <feMergeNode/>
        <feMergeNode in="SourceGraphic"/>
      </feMerge>
    </filter>
  </defs>
  
  <!-- Product visualization -->
  %%PRODUCT_SHAPE%%
  
  <!-- Product label -->
  <rect x="50" y="230" width="200" height="40" rx="5" fill="rgba(255,255,255,0.9)" stroke="#ddd"/>
  <text x="150" y="255" font-family="Arial, sans-serif" font-size="14" font-weight="bold" text-anchor="middle" fill="#333">%%PRODUCT_NAME%%</text>
</svg>',
    
    'cosmetics' => '<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="300" xmlns="http://www.w3.org/2000/svg">
  <rect width="100%" height="100%" fill="#f8f9fa"/>
  <rect x="0" y="0" width="300" height="300" fill="url(#bgGradient)" opacity="0.3"/>
  
  <!-- Background gradient -->
  <defs>
    <linearGradient id="bgGradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#e9ecef;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#dee2e6;stop-opacity:1" />
    </linearGradient>
    <linearGradient id="productGradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:%%COLOR%%;stop-opacity:0.8" />
      <stop offset="100%" style="stop-color:%%DARK_COLOR%%;stop-opacity:0.9" />
    </linearGradient>
    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
      <feGaussianBlur in="SourceAlpha" stdDeviation="2"/>
      <feOffset dx="1" dy="1" result="offsetblur"/>
      <feFlood flood-color="rgba(0,0,0,0.2)"/>
      <feComposite in2="offsetblur" operator="in"/>
      <feMerge>
        <feMergeNode/>
        <feMergeNode in="SourceGraphic"/>
      </feMerge>
    </filter>
  </defs>
  
  <!-- Product visualization -->
  %%PRODUCT_SHAPE%%
  
  <!-- Product label -->
  <rect x="50" y="230" width="200" height="40" rx="5" fill="rgba(255,255,255,0.9)" stroke="#ddd"/>
  <text x="150" y="255" font-family="Arial, sans-serif" font-size="14" font-weight="bold" text-anchor="middle" fill="#333">%%PRODUCT_NAME%%</text>
</svg>'
];

// Product shapes for different categories
$productShapes = [
    'food' => [
        'organik-zeytinyagi' => '<ellipse cx="150" cy="140" rx="80" ry="100" fill="url(#productGradient)" filter="url(#shadow)"/>
        <path d="M100 120 Q150 80 200 120 Q150 160 100 120" fill="#1a5d1a" opacity="0.3"/>
        <path d="M120 150 Q150 130 180 150 Q150 170 120 150" fill="#1a5d1a" opacity="0.2"/>
        <ellipse cx="150" cy="180" rx="30" ry="15" fill="#8B4513" opacity="0.4"/>',
        
        'bal' => '<path d="M150 80 C100 120, 100 180, 150 220 C200 180, 200 120, 150 80 Z" fill="url(#productGradient)" filter="url(#shadow)"/>
        <ellipse cx="150" cy="150" rx="40" ry="60" fill="#D2691E" opacity="0.3"/>
        <circle cx="130" cy="130" r="8" fill="#8B4513" opacity="0.4"/>
        <circle cx="170" cy="130" r="8" fill="#8B4513" opacity="0.4"/>
        <path d="M140 160 Q150 170 160 160" stroke="#8B4513" stroke-width="3" fill="none"/>',
        
        'ton-baligi' => '<rect x="90" y="100" width="120" height="80" rx="10" fill="url(#productGradient)" filter="url(#shadow)"/>
        <rect x="95" y="105" width="110" height="20" rx="5" fill="#556B2F" opacity="0.3"/>
        <rect x="95" y="135" width="110" height="15" rx="3" fill="#556B2F" opacity="0.2"/>
        <rect x="95" y="155" width="110" height="15" rx="3" fill="#556B2F" opacity="0.2"/>
        <circle cx="120" cy="125" r="5" fill="#fff" opacity="0.7"/>
        <circle cx="180" cy="125" r="5" fill="#fff" opacity="0.7"/>'
    ],
    
    'electronics' => [
        'akilli-telefon' => '<rect x="100" y="80" width="100" height="160" rx="15" fill="url(#productGradient)" filter="url(#shadow)"/>
        <rect x="105" y="85" width="90" height="150" rx="10" fill="#000" opacity="0.1"/>
        <rect x="130" y="90" width="40" height="5" rx="2" fill="#333"/>
        <rect x="110" y="105" width="80" height="100" rx="5" fill="#1a1a1a" opacity="0.2"/>
        <circle cx="150" cy="225" r="5" fill="#333"/>',
        
        'dizustu-bilgisayar' => '<rect x="80" y="100" width="140" height="100" rx="10" fill="url(#productGradient)" filter="url(#shadow)"/>
        <rect x="85" y="105" width="130" height="70" fill="#000" opacity="0.1"/>
        <rect x="70" y="170" width="160" height="10" rx="5" fill="#333"/>
        <rect x="90" y="180" width="120" height="3" fill="#666"/>
        <circle cx="100" cy="185" r="2" fill="#fff"/>
        <circle cx="110" cy="185" r="2" fill="#fff"/>
        <circle cx="120" cy="185" r="2" fill="#fff"/>'
    ],
    
    'clothing' => [
        'tisort' => '<path d="M100 100 L120 80 L180 80 L200 100 L200 200 L100 200 Z" fill="url(#productGradient)" filter="url(#shadow)"/>
        <path d="M120 80 L130 60 L170 60 L180 80" fill="#fff" opacity="0.3"/>
        <circle cx="135" cy="120" r="15" fill="#fff" opacity="0.3"/>
        <circle cx="165" cy="120" r="15" fill="#fff" opacity="0.3"/>
        <path d="M140 150 Q150 170 160 150" stroke="#fff" stroke-width="3" fill="none" opacity="0.3"/>',
        
        'kot-pantolon' => '<path d="M120 100 L120 200 L140 200 L150 180 L160 200 L180 200 L180 100 Z" fill="url(#productGradient)" filter="url(#shadow)"/>
        <path d="M120 100 L100 120 L120 120 Z" fill="#4682B4" opacity="0.3"/>
        <path d="M180 100 L200 120 L180 120 Z" fill="#4682B4" opacity="0.3"/>
        <rect x="135" y="110" width="30" height="40" fill="#87CEEB" opacity="0.2"/>
        <line x1="140" y1="160" x2="140" y2="190" stroke="#fff" stroke-width="2" opacity="0.3"/>
        <line x1="160" y1="160" x2="160" y2="190" stroke="#fff" stroke-width="2" opacity="0.3"/>'
    ],
    
    'cosmetics' => [
        'dogal-sampuan' => '<path d="M130 80 Q150 60 170 80 L180 180 Q150 200 120 180 Z" fill="url(#productGradient)" filter="url(#shadow)"/>
        <ellipse cx="150" cy="90" rx="15" ry="25" fill="#fff" opacity="0.3"/>
        <rect x="140" y="120" width="20" height="40" fill="#9370DB" opacity="0.2"/>
        <path d="M135 170 Q150 180 165 170" stroke="#fff" stroke-width="2" fill="none" opacity="0.4"/>',
        
        'organik-krem' => '<rect x="120" y="100" width="60" height="100" rx="30" fill="url(#productGradient)" filter="url(#shadow)"/>
        <ellipse cx="150" cy="120" rx="20" ry="25" fill="#fff" opacity="0.3"/>
        <rect x="135" y="150" width="30" height="30" rx="5" fill="#fff" opacity="0.2"/>
        <circle cx="150" cy="165" r="8" fill="#DDA0DD" opacity="0.3"/>'
    ]
];

// Function to darken a color
function darkenColor($color) {
    // Remove # if present
    $color = str_replace('#', '', $color);
    
    // Convert to RGB
    if (strlen($color) == 6) {
        $r = hexdec(substr($color, 0, 2));
        $g = hexdec(substr($color, 2, 2));
        $b = hexdec(substr($color, 4, 2));
    } else {
        return '#' . $color;
    }
    
    // Darken by 20%
    $r = max(0, $r - 51);
    $g = max(0, $g - 51);
    $b = max(0, $b - 51);
    
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}

// Create SVG images for each product
foreach ($products as $product) {
    $imageName = $product['image_name'];
    $category = $product['category'];
    $color = $product['color'];
    $darkColor = darkenColor($color);
    
    // Get the appropriate template
    $svgTemplate = $svgTemplates[$category];
    
    // Get the appropriate shape for this product
    $shape = '';
    if (isset($productShapes[$category][$imageName])) {
        $shape = $productShapes[$category][$imageName];
    } else {
        // Default shape if specific one not found
        $shape = '<circle cx="150" cy="150" r="60" fill="url(#productGradient)" filter="url(#shadow)"/>';
    }
    
    // Replace placeholders
    $svgContent = str_replace(
        ['%%PRODUCT_NAME%%', '%%COLOR%%', '%%DARK_COLOR%%', '%%PRODUCT_SHAPE%%'],
        [$product['name'], $color, $darkColor, $shape],
        $svgTemplate
    );
    
    // Save SVG file
    file_put_contents($imagesDir . $imageName . '.svg', $svgContent);
    
    // Also create a simple JPG placeholder (just a small text file for now)
    $jpgContent = "Product: " . $product['name'] . "\nImage: " . $imageName;
    file_put_contents($imagesDir . $imageName . '.jpg', $jpgContent);
    
    echo "Created images for: " . $product['name'] . "\n";
}

echo "All product images created successfully with proper visuals!\n";
?>