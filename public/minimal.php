<?php

// Minimal Laravel bootstrap for Vercel
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

define('LARAVEL_START', microtime(true));

// Create basic app instance
$app = new Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// Basic service bindings
$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

// Set environment
$app['env'] = 'production';
$app['config']['app.env'] = 'production';
$app['config']['app.debug'] = false;

// Simple route for testing
$router = new Router($app['events']);
$router->get('/', function () {
    return 'Laravel is running on Vercel!';
});

$router->get('/admin', function () {
    return 'Admin panel would be here';
});

// Handle request
try {
    $request = Request::capture();
    $response = $router->dispatch($request);
    $response->send();
} catch (Exception $e) {
    http_response_code(500);
    echo 'Error: ' . $e->getMessage();
}