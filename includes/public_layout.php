<?php
/**
 * Public Layout Template
 */
if (!function_exists('renderPublicLayout')):
function renderPublicLayout($title, $content) {
    $config = require __DIR__ . '/../config/config.php';
    $appName = htmlspecialchars($config['app']['name'], ENT_QUOTES, 'UTF-8');
    $pageTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    
    // Generate site URL
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $scriptPath = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['SCRIPT_NAME']);
    $baseUrl = $protocol . '://' . $host . $scriptPath;
    
    // Get categories for navigation
    $categories = getActiveCategories();
    
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $pageTitle . ' | ' . $appName . '</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="' . $baseUrl . 'css/styles.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="' . $baseUrl . '">
                    <i class="fas fa-cube"></i> ' . $appName . '
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="' . $baseUrl . '">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                Categories
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="' . $baseUrl . '">All Products</a></li>';
    
    foreach ($categories as $cat) {
        echo '<li><a class="dropdown-item" href="' . $baseUrl . 'index.php?category=' . htmlspecialchars($cat['slug'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') . '</a></li>';
    }
    
    echo '                            </ul>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="' . $baseUrl . 'admin/login.php">
                                <i class="fas fa-user-shield"></i> Admin
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    
    <main>
        ' . $content . '
    </main>
    
    <footer class="site-footer">
        <div class="container">
            <p>&copy; ' . date('Y') . ' ' . $appName . '. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
    <script src="' . $baseUrl . 'js/app.js"></script>
</body>
</html>';
}
endif;
