<?php
/**
 * Database Connection
 * Uses PDO with proper error handling
 */

$config = require __DIR__ . '/../config/config.php';

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'] . ';charset=' . $config['db']['charset'];
    
    $pdo = new PDO($dsn, $config['db']['user'], $config['db']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
} catch (PDOException $e) {
    if ($config['debug']) {
        die('Database connection failed: ' . $e->getMessage());
    } else {
        die('Database unavailable. Please check the database configuration.');
    }
}
