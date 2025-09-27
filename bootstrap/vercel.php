<?php

// Vercel-specific Laravel bootstrap
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
}

// Override storage paths for Vercel
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/views';
$_ENV['CACHE_STORE'] = 'file';
$_ENV['SESSION_DRIVER'] = 'file';

// Create necessary directories
$directories = [
    '/tmp/storage',
    '/tmp/storage/app',
    '/tmp/storage/framework',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/views',
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
}

// Override Laravel's storage_path function for Vercel
if (!function_exists('storage_path_override')) {
    function storage_path_override($path = '') {
        $storagePath = '/tmp/storage';
        return $path ? $storagePath . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : $storagePath;
    }
    
    // Create a bootstrap file for config caching
    if (!file_exists('/tmp/storage/framework/cache/config.php')) {
        file_put_contents('/tmp/storage/framework/cache/config.php', '<?php return [];');
    }
}

// Run database migrations if needed
require_once __DIR__ . '/migrate.php';

// Create admin user if needed
require_once __DIR__ . '/seed.php';