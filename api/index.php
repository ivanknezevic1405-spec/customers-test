<?php

// Vercel PHP handler for Laravel
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

// Load Vercel-specific bootstrap
require_once __DIR__ . '/../bootstrap/vercel.php';

// Require the Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Handle the request
try {
    $kernel = $app->make(Kernel::class);
    
    $request = Request::capture();
    $response = $kernel->handle($request);
    $response->send();
    
    $kernel->terminate($request, $response);
} catch (Exception $e) {
    error_log("Laravel Error: " . $e->getMessage());
    http_response_code(500);
    echo "Application Error";
}