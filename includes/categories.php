<?php
/**
 * Category Helper Functions
 */

function getActiveCategories() {
    global $pdo;
    
    $stmt = $pdo->query('SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order ASC');
    return $stmt->fetchAll();
}

function getCategoryBySlug($slug) {
    global $pdo;
    
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE slug = ? AND is_active = 1');
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function getAllCategories() {
    global $pdo;
    
    $stmt = $pdo->query('SELECT * FROM categories ORDER BY sort_order ASC');
    return $stmt->fetchAll();
}

function createCategory($data) {
    global $pdo;
    
    $stmt = $pdo->prepare('INSERT INTO categories (slug, name, sort_order, is_active) VALUES (?, ?, ?, ?)');
    return $stmt->execute([
        $data['slug'],
        $data['name'],
        $data['sort_order'],
        isset($data['is_active']) ? 1 : 0
    ]);
}

function updateCategory($id, $data) {
    global $pdo;
    
    // Check if category has products
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM products WHERE category_id = ?');
    $stmt->execute([$id]);
    $productCount = $stmt->fetchColumn();
    
    if ($productCount > 0 && isset($data['slug'])) {
        return ['success' => false, 'message' => 'Cannot change slug of category with products'];
    }
    
    $stmt = $pdo->prepare('UPDATE categories SET name = ?, sort_order = ?, is_active = ? WHERE id = ?');
    return $stmt->execute([
        $data['name'],
        $data['sort_order'],
        isset($data['is_active']) ? 1 : 0,
        $id
    ]);
}

function deleteCategory($id) {
    global $pdo;
    
    // Check if category has products
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM products WHERE category_id = ?');
    $stmt->execute([$id]);
    $productCount = $stmt->fetchColumn();
    
    if ($productCount > 0) {
        return ['success' => false, 'message' => 'Cannot delete category with existing products'];
    }
    
    $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
    return $stmt->execute([$id]);
}
