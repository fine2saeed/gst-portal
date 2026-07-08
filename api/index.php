<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Point to the project root (one level up from /api)
$projectRoot = __DIR__ . '/..';

// Ensure writable directories exist in /tmp for serverless
$storageDirs = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/sessions',
    '/tmp/framework/cache',
    '/tmp/framework/sessions',
    '/tmp/framework/views',
    '/tmp/logs',
    '/tmp/app/public',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Register the Composer autoloader
require $projectRoot . '/vendor/autoload.php';

// Bootstrap Laravel and handle the request (Laravel 12 style)
/** @var Application $app */
$app = require_once $projectRoot . '/bootstrap/app.php';

$app->useStoragePath('/tmp');

$app->handleRequest(Request::capture());
