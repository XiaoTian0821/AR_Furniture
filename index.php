<?php
/**
 * Application Entry Point - Index Page
 */
define('APP_ROOT', __DIR__);
require_once APP_ROOT . '/includes/db.php';
require_once APP_ROOT . '/includes/auth.php';
require_once APP_ROOT . '/includes/categories.php';
require_once APP_ROOT . '/includes/products.php';
require_once APP_ROOT . '/includes/public_layout.php';

define('APP_URL', 'http://localhost/AR_Furniture/');

// Get active categories and products
$categories = getActiveCategories();
$categoryId = isset($_GET['category']) ? $_GET['category'] : null;
$products = [];

if ($categoryId) {
    // Get category details
    $category = getCategoryBySlug($categoryId);
    if ($category) {
        $categoryIdInt = (int)$category['id'];
        $products = getActiveProducts($categoryIdInt);
    }
} else {
    $products = getActiveProducts();
}

// Build content
$siteUrl = 'http://localhost/AR_Furniture/index.php';

$content = '<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1>See It Before You Buy</h1>
            <p>Preview furniture in your own space with Augmented Reality. 
               Browse our collection, view 3D models, and place products in your room using your phone.</p>
            <a href="#products" class="btn btn-primary btn-lg">Explore Collection</a>
        </div>
    </div>
</section>

<section class="qr-section">
    <div class="container">
        <div class="qr-container">
            <h2>View on Your Phone</h2>
            <p>Scan the QR code below to open this catalog on your mobile device</p>
            <div class="qr-code">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($siteUrl) . '" 
                     alt="QR Code to view on mobile" 
                     width="200" 
                     height="200">
            </div>
            <p class="qr-hint">Point your phone camera at the QR code to open the catalog</p>
        </div>
    </div>
</section>

<section id="products" class="products-section">
    <div class="container">
        <div class="section-header">
            <h2>' . ($categoryId ? htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') . ' Collection' : 'Our Collection') . '</h2>';

// Category filter buttons
$content .= '<div class="category-filters">
    <a href="' . APP_URL . 'index.php" class="filter-btn' . (!$categoryId ? ' active' : '') . '">All</a>';

foreach ($categories as $cat) {
    $activeClass = ($categoryId === $cat['slug']) ? ' active' : '';
    $content .= '<a href="' . APP_URL . 'index.php?category=' . htmlspecialchars($cat['slug'], ENT_QUOTES, 'UTF-8') . '" class="filter-btn' . $activeClass . '">' . htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') . '</a>';
}

$content .= '</div></div>';

// Product grid
if (!empty($products)) {
    $content .= '<div class="product-grid">';
    
    foreach ($products as $product) {
        $thumbPath = $product['thumb_path'] ? APP_URL . $product['thumb_path'] : 'https://via.placeholder.com/400x300/e8e8e8/666?text=No+Image';
        $content .= '
        <div class="product-card">
            <div class="product-image">
                <img src="' . htmlspecialchars($thumbPath, ENT_QUOTES, 'UTF-8') . '" 
                     alt="' . htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') . '" 
                     loading="lazy">
            </div>
            <div class="product-info">
                <span class="product-category">' . htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8') . '</span>
                <h3>' . htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') . '</h3>
                <p class="product-price">' . formatPrice($product['price'], $product['currency']) . '</p>
                <a href="' . APP_URL . 'product.php?slug=' . htmlspecialchars($product['slug'], ENT_QUOTES, 'UTF-8') . '" class="btn btn-view">
                    View in Your Space <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>';
    }
    
    $content .= '</div>';
} else {
    $content .= '<p class="no-products">No products found in this category.</p>';
}

$content .= '</section>';

// Render the page
renderPublicLayout('Home', $content);
