<?php
/**
 * Product Helper Functions
 */

function getActiveProducts($categoryId = null, $limit = null) {
    global $pdo;
    
    $sql = 'SELECT p.*, c.slug as category_slug, c.name as category_name 
            FROM products p 
            INNER JOIN categories c ON p.category_id = c.id 
            WHERE p.is_active = 1 AND c.is_active = 1';
    
    $params = [];
    
    if ($categoryId) {
        $sql .= ' AND p.category_id = ?';
        $params[] = $categoryId;
    }
    
    $sql .= ' ORDER BY p.sort_order ASC, p.created_at DESC';
    
    if ($limit) {
        $sql .= ' LIMIT ?';
        $params[] = $limit;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getProductBySlug($slug) {
    global $pdo;
    
    $stmt = $pdo->prepare('SELECT p.*, c.name as category_name, c.slug as category_slug 
                           FROM products p 
                           INNER JOIN categories c ON p.category_id = c.id 
                           WHERE p.slug = ? AND p.is_active = 1');
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function formatPrice($price, $currency = 'RM') {
    return $currency . ' ' . number_format($price, 2);
}

function getProductDimensions($product) {
    $dims = [];
    
    if ($product['width_cm']) $dims[] = 'W: ' . number_format($product['width_cm'], 1) . ' cm';
    if ($product['height_cm']) $dims[] = 'H: ' . number_format($product['height_cm'], 1) . ' cm';
    if ($product['depth_cm']) $dims[] = 'D: ' . number_format($product['depth_cm'], 1) . ' cm';
    
    return implode(' | ', $dims);
}

function generateSafeFilename($extension) {
    return bin2hex(random_bytes(16)) . $extension;
}

function uploadFile($file, $targetDir, $allowedExtensions) {
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'message' => 'Invalid file upload'];
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Upload error occurred'];
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array('.' . $extension, $allowedExtensions)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }
    
    // Check file size
    if ($file['size'] > 50 * 1024 * 1024) {
        return ['success' => false, 'message' => 'File too large (max 50MB)'];
    }
    
    // Generate safe filename
    $safeName = generateSafeFilename('.' . $extension);
    $targetPath = __DIR__ . '/../' . $targetDir . '/' . $safeName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'path' => $targetDir . '/' . $safeName];
    }
    
    return ['success' => false, 'message' => 'Failed to save file'];
}
