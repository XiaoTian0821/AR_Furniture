<?php
/**
 * Product Delete Handler
 */
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

requireAdminLogin();

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

// Get product details
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: index.php');
    exit;
}

// Delete files if they exist
if ($product['glb_path']) {
    $glbPath = __DIR__ . '/../' . $product['glb_path'];
    if (file_exists($glbPath)) {
        @unlink($glbPath);
    }
}

if ($product['usdz_path']) {
    $usdzPath = __DIR__ . '/../' . $product['usdz_path'];
    if (file_exists($usdzPath)) {
        @unlink($usdzPath);
    }
}

if ($product['thumb_path']) {
    $thumbPath = __DIR__ . '/../' . $product['thumb_path'];
    if (file_exists($thumbPath)) {
        @unlink($thumbPath);
    }
}

// Delete from database
$stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
$stmt->execute([$id]);

header('Location: product_edit.php?deleted=1');
exit;
