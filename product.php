<?php
/**
 * Product Detail Page with 3D Model Viewer and AR
 */
define('APP_ROOT', __DIR__);
require_once APP_ROOT . '/includes/db.php';
require_once APP_ROOT . '/includes/auth.php';
require_once APP_ROOT . '/includes/categories.php';
require_once APP_ROOT . '/includes/products.php';
require_once APP_ROOT . '/includes/public_layout.php';

define('APP_URL', 'http://localhost/AR_Furniture/');

$slug = $_GET['slug'] ?? '';
$product = getProductBySlug($slug);

if (!$product) {
    http_response_code(404);
    $content = '<div class="error-page">
        <h2>Product Not Found</h2>
        <p>The product you are looking for does not exist or has been removed.</p>
        <a href="index.php" class="btn btn-primary">Back to Catalog</a>
    </div>';
    renderPublicLayout('Product Not Found', $content);
    exit;
}

// Generate QR code URL
$qrUrl = APP_URL . 'product.php?slug=' . urlencode($product['slug']);

// Generate QR code URL
$qrUrl = APP_URL . 'product.php?slug=' . urlencode($product['slug']);

// Build content
$content = '<div class="product-detail">
    <div class="product-viewer">
        <h2 class="product-detail-title">' . htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') . '</h2>
        
        <div class="model-viewer-container">
            <model-viewer
                id="productModel"
                src="' . ($product['glb_path'] ? APP_URL . $product['glb_path'] : '') . '"
                poster="' . ($product['thumb_path'] ? APP_URL . $product['thumb_path'] : 'https://via.placeholder.com/600x400/e8e8e8/666?text=3D+Model+Unavailable') . '"
                alt="3D model of ' . htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') . '"
                camera-controls
                ar
                ar-modes="webxr scene-viewer quick-look"
                shadow-intensity="1"
                auto-rotate
                rotation-per-second="30deg"
                style="width: 100%; height: 500px; background: #f5f5f5; border-radius: 12px;"
            >
                <button slot="ar-button" class="ar-button">
                    <i class="fas fa-vr-cardboard"></i> View in Your Space
                </button>
            </model-viewer>
        </div>
        
        <div class="ar-status-message" id="arStatus">
            <i class="fas fa-info-circle"></i>
            <span>AR is supported on compatible mobile devices. Open this page on your phone and tap the AR button.</span>
        </div>
    </div>
    
    <div class="product-info-sidebar">
        <div class="product-meta">
            <span class="product-category-badge">' . htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8') . '</span>
            <h1>' . htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') . '</h1>
            <p class="product-price-large">' . formatPrice($product['price'], $product['currency']) . '</p>
        </div>
        
        <div class="product-description">
            <h3>Description</h3>
            <p>' . nl2br(htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8')) . '</p>
        </div>
        
        <div class="product-dimensions">
            <h3>Dimensions</h3>
            <ul>
                ' . ($product['width_cm'] ? '<li><strong>Width:</strong> ' . number_format($product['width_cm'], 1) . ' cm</li>' : '') . '
                ' . ($product['height_cm'] ? '<li><strong>Height:</strong> ' . number_format($product['height_cm'], 1) . ' cm</li>' : '') . '
                ' . ($product['depth_cm'] ? '<li><strong>Depth:</strong> ' . number_format($product['depth_cm'], 1) . ' cm</li>' : '') . '
            </ul>
        </div>
        
        <div class="product-actions">
            <button class="btn btn-ar-large" onclick="launchAR()">
                <i class="fas fa-vr-cardboard"></i> Launch AR Experience
            </button>
        </div>
        
        <div class="qr-section">
            <h3>View on Mobile</h3>
            <p>Scan to open on your phone</p>
            <div class="qr-code-inline">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($qrUrl) . '" 
                     alt="QR Code" 
                     width="150" 
                     height="150">
            </div>
        </div>
    </div>
</div>';

renderPublicLayout($product['name'], $content);
