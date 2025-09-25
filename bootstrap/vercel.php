<?php

// Vercel-specific bootstrap
// Set up environment for serverless execution

// Ensure proper paths for Vercel
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
}

// Set up proper storage paths for Vercel
if (!defined('STORAGE_PATH')) {
    define('STORAGE_PATH', '/tmp/storage');
}

// Create necessary directories in /tmp for Vercel
$dirs = [
    '/tmp/storage',
    '/tmp/storage/app',
    '/tmp/storage/framework',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Override storage path
if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return '/tmp/storage' . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : '');
    }
}