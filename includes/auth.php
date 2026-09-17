<?php
/**
 * Authentication Helper Functions
 */

function startAuthSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function isAdminLoggedIn() {
    startAuthSession();
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_username']);
}

function requireAdminLogin() {
    startAuthSession();
    if (!isAdminLoggedIn()) {
        header('Location: /AR_Furniture/admin/login.php');
        exit;
    }
}

function loginAdmin($username, $password) {
    startAuthSession();
    
    global $pdo;
    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        session_regenerate_id(true);
        return true;
    }
    
    return false;
}

function logoutAdmin() {
    startAuthSession();
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

function getAdminUsername() {
    startAuthSession();
    return $_SESSION['admin_username'] ?? 'Guest';
}
