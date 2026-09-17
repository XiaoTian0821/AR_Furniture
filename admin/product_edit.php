<?php
/**
 * Product Management (Add/Edit)
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/products.php';
require_once __DIR__ . '/../includes/categories.php';
require_once __DIR__ . '/../includes/admin_layout.php';

requireAdminLogin();

$id = $_GET['id'] ?? null;
$product = null;
$editMode = false;

if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    if ($product) {
        $editMode = true;
    }
}

$categories = getAllCategories();

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file uploads
    $glbPath = null;
    $usdzPath = null;
    $thumbPath = null;
    
    if (isset($_FILES['glb_file']) && $_FILES['glb_file']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadFile($_FILES['glb_file'], 'models', ['.glb', '.gltf']);
        if ($uploadResult['success']) {
            $glbPath = $uploadResult['path'];
        } else {
            $message = $uploadResult['message'];
            $messageType = 'error';
        }
    }
    
    if (isset($_FILES['usdz_file']) && $_FILES['usdz_file']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadFile($_FILES['usdz_file'], 'models', ['.usdz']);
        if ($uploadResult['success']) {
            $usdzPath = $uploadResult['path'];
        }
    }
    
    if (isset($_FILES['thumb_file']) && $_FILES['thumb_file']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadFile($_FILES['thumb_file'], 'uploads/thumbs', ['.jpg', '.jpeg', '.png', '.webp']);
        if ($uploadResult['success']) {
            $thumbPath = $uploadResult['path'];
        }
    }
    
    if (empty($message) || $messageType !== 'error') {
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $currency = trim($_POST['currency'] ?? 'RM');
        $widthCm = $_POST['width_cm'] ?? null;
        $heightCm = $_POST['height_cm'] ?? null;
        $depthCm = $_POST['depth_cm'] ?? null;
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        
        // Generate slug if empty
        if (empty($slug)) {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9-]/', '-', $name));
            $slug = preg_replace('/-+/', '-', $slug);
        }
        
        // Keep existing paths if not uploading new files
        if (!$glbPath && $product) {
            $glbPath = $product['glb_path'];
        }
        if (!$usdzPath && $product) {
            $usdzPath = $product['usdz_path'];
        }
        if (!$thumbPath && $product) {
            $thumbPath = $product['thumb_path'];
        }
        
        try {
            if ($editMode) {
                $stmt = $pdo->prepare('
                    UPDATE products SET 
                        category_id = ?, slug = ?, name = ?, description = ?, 
                        price = ?, currency = ?, glb_path = ?, usdz_path = ?, 
                        thumb_path = ?, width_cm = ?, height_cm = ?, depth_cm = ?,
                        sort_order = ?, is_active = ?
                    WHERE id = ?
                ');
                $stmt->execute([
                    $categoryId, $slug, $name, $description, $price, $currency,
                    $glbPath, $usdzPath, $thumbPath, $widthCm, $heightCm, $depthCm,
                    $sortOrder, $isActive, $id
                ]);
                $message = 'Product updated successfully.';
                $messageType = 'success';
            } else {
                // Check if slug already exists
                $stmt = $pdo->prepare('SELECT COUNT(*) FROM products WHERE slug = ?');
                $stmt->execute([$slug]);
                if ($stmt->fetchColumn() > 0) {
                    $message = 'Slug already exists. Please choose a different slug.';
                    $messageType = 'error';
                } else {
                    $stmt = $pdo->prepare('
                        INSERT INTO products 
                        (category_id, slug, name, description, price, currency, glb_path, usdz_path, thumb_path, 
                         width_cm, height_cm, depth_cm, sort_order, is_active)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ');
                    $stmt->execute([
                        $categoryId, $slug, $name, $description, $price, $currency,
                        $glbPath, $usdzPath, $thumbPath, $widthCm, $heightCm, $depthCm,
                        $sortOrder, $isActive
                    ]);
                    $message = 'Product created successfully.';
                    $messageType = 'success';
                }
            }
        } catch (PDOException $e) {
            $message = 'Database error: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

$submitUrl = $editMode ? 'product_edit.php?id=' . $id : 'product_edit.php';
$title = $editMode ? 'Edit Product' : 'Add Product';

$content = '
<div class="row">
    <div class="col-12">
        <h2>' . $title . '</h2>
    </div>
</div>

<div class="admin-card">
    <h3>Product Information</h3>';

if ($message) {
    $content .= '<div class="alert alert-' . $messageType . '">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</div>';
}

$content .= '
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Product Name *</label>
                    <input type="text" id="name" name="name" required 
                           value="' . ($product ? htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') : '') . '"
                           placeholder="e.g., Modern Armchair">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="slug">URL Slug *</label>
                    <input type="text" id="slug" name="slug" required 
                           value="' . ($product ? htmlspecialchars($product['slug'], ENT_QUOTES, 'UTF-8') : '') . '"
                           placeholder="e.g., modern-armchair">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="category_id">Category *</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Select Category</option>';

foreach ($categories as $cat) {
    $selected = ($product && $product['category_id'] == $cat['id']) ? 'selected' : '';
    $content .= '<option value="' . $cat['id'] . '" ' . $selected . '>' . htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') . '</option>';
}

$content .= '
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="price">Price *</label>
                    <input type="number" id="price" name="price" step="0.01" required 
                           value="' . ($product ? $product['price'] : '') . '"
                           placeholder="0.00">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="currency">Currency</label>
                    <input type="text" id="currency" name="currency" 
                           value="' . ($product ? htmlspecialchars($product['currency'], ENT_QUOTES, 'UTF-8') : 'RM') . '"
                           placeholder="RM">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" 
                           value="' . ($product ? $product['sort_order'] : '0') . '">
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" 
                      placeholder="Product description...">' . ($product ? htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8') : '') . '</textarea>
        </div>
        
        <h4>Dimensions (in cm)</h4>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="width_cm">Width</label>
                    <input type="number" id="width_cm" name="width_cm" step="0.1"
                           value="' . ($product ? $product['width_cm'] : '') . '">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="height_cm">Height</label>
                    <input type="number" id="height_cm" name="height_cm" step="0.1"
                           value="' . ($product ? $product['height_cm'] : '') . '">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="depth_cm">Depth</label>
                    <input type="number" id="depth_cm" name="depth_cm" step="0.1"
                           value="' . ($product ? $product['depth_cm'] : '') . '">
                </div>
            </div>
        </div>
        
        <h4>3D Models</h4>
        <div class="form-group">
            <label for="glb_file">GLB Model (.glb, .gltf) - Max 50MB</label>
            <input type="file" id="glb_file" name="glb_file" accept=".glb,.gltf">';

if ($product && $product['glb_path']) {
    $content .= '<small>Current: ' . htmlspecialchars($product['glb_path'], ENT_QUOTES, 'UTF-8') . '</small>';
}

$content .= '
        </div>
        
        <div class="form-group">
            <label for="usdz_file">USDZ Model (.usdz) - Optional</label>
            <input type="file" id="usdz_file" name="usdz_file" accept=".usdz">';

if ($product && $product['usdz_path']) {
    $content .= '<small>Current: ' . htmlspecialchars($product['usdz_path'], ENT_QUOTES, 'UTF-8') . '</small>';
}

$content .= '
        </div>
        
        <div class="form-group">
            <label for="thumb_file">Thumbnail Image (.jpg, .jpeg, .png, .webp)</label>
            <input type="file" id="thumb_file" name="thumb_file" accept=".jpg,.jpeg,.png,.webp">';

if ($product && $product['thumb_path']) {
    $content .= '<small>Current: ' . htmlspecialchars($product['thumb_path'], ENT_QUOTES, 'UTF-8') . '</small>';
}

$content .= '
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" ' . 
                ($product ? ($product['is_active'] ? 'checked' : '') : 'checked') . 
                '> Active (Visible in storefront)
            </label>
        </div>
        
        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> ' . ($editMode ? 'Update' : 'Create') . ' Product
            </button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>';

renderAdminLayout($title, $content);
