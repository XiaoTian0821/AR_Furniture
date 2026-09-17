<?php
/**
 * Application Configuration
 * Edit these settings for your environment
 */

return [
    'app' => [
        'name' => 'AR Furniture Catalog',
        'url' => '', // Leave empty for auto-detection, or set to your production URL
    ],
    'db' => [
        'host' => 'localhost',
        'name' => 'ar_furniture',
        'user' => 'root',
        'pass' => '123456',
        'charset' => 'utf8mb4',
    ],
    'upload' => [
        'max_size' => 50 * 1024 * 1024, // 50MB
        'allowed_models' => ['.glb', '.gltf', '.usdz'],
        'allowed_images' => ['.jpg', '.jpeg', '.png', '.webp'],
    ],
    'debug' => false, // Set to true for development, false for production
];
