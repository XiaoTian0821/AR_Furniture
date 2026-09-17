<?php
/**
 * Admin Layout Template
 */
if (!function_exists('renderAdminLayout')):
function renderAdminLayout($title, $content) {
    $pageTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - ' . $pageTitle . ' | AR Furniture</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/AR_Furniture/css/styles.css">
</head>
<body>
    <nav class="admin-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="/AR_Furniture/admin/">
                <i class="fas fa-cube"></i> AR Furniture Admin
            </a>
            <div class="admin-nav-links">
                <a href="/AR_Furniture/admin/"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="/AR_Furniture/admin/product_edit.php"><i class="fas fa-plus"></i> Add Product</a>
                <a href="/AR_Furniture/admin/categories.php"><i class="fas fa-list"></i> Categories</a>
                <a href="/AR_Furniture/"><i class="fas fa-store"></i> Storefront</a>
                <a href="/AR_Furniture/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="admin-content">
        <div class="container-fluid">';
    echo $content;
    echo '
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>';
}
endif;
