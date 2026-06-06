<?php

/**
 * Point d'entrée InfinityFree (htdocs/index.php).
 * Laravel dans htdocs/blog_personnel/ ou htdocs/blog_personnel_2/
 * build/, .htaccess et storage/ à la racine de htdocs/
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$laravel = null;

foreach (['blog_personnel_2', 'blog_personnel'] as $dossier) {
    $chemin = __DIR__.'/'.$dossier;
    if (is_file($chemin.'/vendor/autoload.php')) {
        $laravel = $chemin;
        break;
    }
}

if ($laravel === null) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    exit(
        "Dossier Laravel introuvable ou vendor/ manquant.\n\n".
        "Uploadez le dossier vendor/ (depuis votre PC) dans :\n".
        "htdocs/blog_personnel_2/vendor/\n\n".
        "Via FileZilla : blog_personnel/vendor → htdocs/blog_personnel_2/vendor"
    );
}

if (file_exists($maintenance = $laravel.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $laravel.'/vendor/autoload.php';

/** @var Application $app */
$app = require_once $laravel.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
