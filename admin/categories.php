<?php
/**
 * Category Management
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/categories.php';
require_once __DIR__ . '/../includes/admin_layout.php';

requireAdminLogin();

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;

$message = '';
$messageType = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $sortOrder = (int)($_POST['sort_order'] ?? 0);
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    
    if (empty($name)) {
        $message = 'Category name is required.';
        $messageType = 'error';
    } else {
        try {
            if (isset($_POST['id']) && $_POST['id']) {
                // Update
                $stmt = $pdo->prepare('UPDATE categories SET name = ?, sort_order = ?, is_active = ? WHERE id = ?');
                $stmt->execute([$name, $sortOrder, $isActive, $_POST['id']]);
                $message = 'Category updated successfully.';
                $messageType = 'success';
            } else {
                // Create
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9-]/', '-', $name));
                $slug = preg_replace('/-+/', '-', $slug);
                
                // Check if slug exists
                $stmt = $pdo->prepare('SELECT COUNT(*) FROM categories WHERE slug = ?');
                $stmt->execute([$slug]);
                if ($stmt->fetchColumn() > 0) {
                    $slug .= '-' . time();
                }
                
                $stmt = $pdo->prepare('INSERT INTO categories (slug, name, sort_order, is_active) VALUES (?, ?, ?, ?)');
                $stmt->execute([$slug, $name, $sortOrder, $isActive]);
                $message = 'Category created successfully.';
                $messageType = 'success';
            }
        } catch (PDOException $e) {
            $message = 'Database error: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Handle delete
if ($action === 'delete' && $id) {
    $result = deleteCategory($id);
    if ($result['success']) {
        $message = 'Category deleted successfully.';
        $messageType = 'success';
    } else {
        $message = $result['message'];
        $messageType = 'error';
    }
}

$editCategory = null;
if ($action === 'edit' && $id) {
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ?');
    $stmt->execute([$id]);
    $editCategory = $stmt->fetch();
}

$content = '
<div class="row">
    <div class="col-12">
        <h2>Category Management</h2>
    </div>
</div>

<div class="admin-card">
    <h3>' . ($editCategory ? 'Edit Category' : 'Add New Category') . '</h3>
    
    ' . ($message ? '<div class="alert alert-' . $messageType . '">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</div>' : '') . '
    
    <form method="POST" action="">
        ' . ($editCategory ? '<input type="hidden" name="id" value="' . $editCategory['id'] . '">' : '') . '
        
        <div class="form-group">
            <label for="name">Category Name *</label>
            <input type="text" id="name" name="name" required 
                   value="' . ($editCategory ? htmlspecialchars($editCategory['name'], ENT_QUOTES, 'UTF-8') : '') . '"
                   placeholder="e.g., Chairs">
        </div>
        
        <div class="form-group">
            <label for="sort_order">Sort Order</label>
            <input type="number" id="sort_order" name="sort_order" 
                   value="' . ($editCategory ? $editCategory['sort_order'] : '0') . '">
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" ' . 
                ($editCategory ? ($editCategory['is_active'] ? 'checked' : '') : 'checked') . '
                > Active
            </label>
        </div>
        
        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> ' . ($editCategory ? 'Update' : 'Create') . '
            </button>
            ' . ($editCategory ? '<a href="categories.php" class="btn btn-secondary">Cancel</a>' : '') . '
        </div>
    </form>
</div>

<div class="admin-card">
    <h3>All Categories</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Sort</th>
                <th>Status</th>
                <th>Products</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>';

$categories = getAllCategories();
foreach ($categories as $cat) {
    // Count products in category
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM products WHERE category_id = ?');
    $stmt->execute([$cat['id']]);
    $productCount = $stmt->fetchColumn();
    
    $statusBadge = $cat['is_active'] ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-warning">Inactive</span>';
    
    $content .= '
            <tr>
                <td>' . $cat['id'] . '</td>
                <td>' . htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($cat['slug'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . $cat['sort_order'] . '</td>
                <td>' . $statusBadge . '</td>
                <td>' . $productCount . '</td>
                <td class="action-buttons">
                    <a href="categories.php?action=edit&id=' . $cat['id'] . '" class="btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="categories.php?action=delete&id=' . $cat['id'] . '" 
                       class="btn-delete"
                       onclick="return confirm(\'Are you sure you want to delete this category?\')">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                </td>
            </tr>';
}

$content .= '
        </tbody>
    </table>
</div>';

renderAdminLayout('Categories', $content);
