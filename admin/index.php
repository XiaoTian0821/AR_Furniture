<?php
/**
 * Admin Dashboard
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/products.php';
require_once __DIR__ . '/../includes/categories.php';
require_once __DIR__ . '/../includes/admin_layout.php';

requireAdminLogin();

// Get statistics
$stmt = $pdo->query('SELECT COUNT(*) FROM products');
$totalProducts = $stmt->fetchColumn();

$stmt = $pdo->query('SELECT COUNT(*) FROM products WHERE is_active = 1');
$activeProducts = $stmt->fetchColumn();

$stmt = $pdo->query('SELECT COUNT(*) FROM categories');
$totalCategories = $stmt->fetchColumn();

$stmt = $pdo->query('SELECT COUNT(*) FROM categories WHERE is_active = 1');
$activeCategories = $stmt->fetchColumn();

$content = '
<div class="row">
    <div class="col-12">
        <h2>Dashboard</h2>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">' . $totalProducts . '</div>
        <div class="stat-label">Total Products</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">' . $activeProducts . '</div>
        <div class="stat-label">Active Products</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">' . $totalCategories . '</div>
        <div class="stat-label">Total Categories</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">' . $activeCategories . '</div>
        <div class="stat-label">Active Categories</div>
    </div>
</div>

<div class="admin-card">
    <h3>Quick Actions</h3>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="product_edit.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Product
        </a>
        <a href="categories.php" class="btn btn-primary">
            <i class="fas fa-list"></i> Manage Categories
        </a>
        <a href="/AR_Furniture/" class="btn btn-secondary" target="_blank">
            <i class="fas fa-store"></i> View Storefront
        </a>
    </div>
</div>

<div class="admin-card">
    <h3>Recent Products</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>';

// Get recent products
$stmt = $pdo->query('SELECT p.*, c.name as category_name 
                     FROM products p 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     ORDER BY p.created_at DESC 
                     LIMIT 10');
$recentProducts = $stmt->fetchAll();

foreach ($recentProducts as $product) {
    $statusBadge = $product['is_active'] ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-warning">Inactive</span>';
    $content .= '
            <tr>
                <td>' . $product['id'] . '</td>
                <td>' . htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . formatPrice($product['price'], $product['currency']) . '</td>
                <td>' . $statusBadge . '</td>
                <td class="action-buttons">
                    <a href="product_edit.php?id=' . $product['id'] . '" class="btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </td>
            </tr>';
}

$content .= '
        </tbody>
    </table>
</div>';

renderAdminLayout('Dashboard', $content);
