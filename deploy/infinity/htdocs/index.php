<?php

/**
 * À placer dans htdocs/ sur InfinityFree.
 * Laravel reste dans htdocs/blog_personnel/
 * build/, .htaccess et storage/ restent dans htdocs/
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$laravel = __DIR__.'/blog_personnel';

if (file_exists($maintenance = $laravel.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $laravel.'/vendor/autoload.php';

/** @var Application $app */
$app = require_once $laravel.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
