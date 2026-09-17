<?php
/**
 * Public API - Products
 * Returns product data as JSON
 */
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/products.php';

try {
    $categoryId = $_GET['category'] ?? null;
    $limit = $_GET['limit'] ?? null;
    
    $products = getActiveProducts($categoryId, $limit);
    
    // Format data for API
    $formattedProducts = [];
    foreach ($products as $product) {
        $formattedProducts[] = [
            'id' => $product['id'],
            'slug' => $product['slug'],
            'name' => $product['name'],
            'description' => $product['description'],
            'price' => (float)$product['price'],
            'currency' => $product['currency'],
            'category' => [
                'id' => $product['category_id'],
                'name' => $product['category_name'],
                'slug' => $product['category_slug']
            ],
            'dimensions' => [
                'width_cm' => $product['width_cm'] ? (float)$product['width_cm'] : null,
                'height_cm' => $product['height_cm'] ? (float)$product['height_cm'] : null,
                'depth_cm' => $product['depth_cm'] ? (float)$product['depth_cm'] : null,
                'glb_x_m' => $product['glb_x_m'] ? (float)$product['glb_x_m'] : null,
                'glb_y_m' => $product['glb_y_m'] ? (float)$product['glb_y_m'] : null,
                'glb_z_m' => $product['glb_z_m'] ? (float)$product['glb_z_m'] : null
            ],
            'models' => [
                'glb' => $product['glb_path'] ? $_SERVER['SCRIPT_NAME'] . '/../' . $product['glb_path'] : null,
                'usdz' => $product['usdz_path'] ? $_SERVER['SCRIPT_NAME'] . '/../' . $product['usdz_path'] : null
            ],
            'thumbnail' => $product['thumb_path'] ? $_SERVER['SCRIPT_NAME'] . '/../' . $product['thumb_path'] : null,
            'active' => (bool)$product['is_active']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'count' => count($formattedProducts),
        'products' => $formattedProducts
    ], JSON_UNESCAPED_UNICODE);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error occurred'
    ]);
}
