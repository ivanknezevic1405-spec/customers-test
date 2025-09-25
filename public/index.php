<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Load Vercel-specific bootstrap for serverless environment
if (isset($_SERVER['VERCEL']) || isset($_ENV['VERCEL']) || isset($_SERVER['AWS_LAMBDA_FUNCTION_NAME'])) {
    require __DIR__.'/../bootstrap/vercel.php';
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
try {
    /** @var Application $app */
    $app = require_once __DIR__.'/../bootstrap/app.php';
    
    $app->handleRequest(Request::capture());
} catch (Exception $e) {
    error_log('Laravel Bootstrap Error: ' . $e->getMessage());
    http_response_code(500);
    echo 'Application Error: ' . $e->getMessage();
}
