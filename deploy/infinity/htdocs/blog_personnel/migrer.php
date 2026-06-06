<?php

/**
 * Script temporaire pour lancer les migrations sur InfinityFree (sans SSH).
 *
 * 1. Uploadez ce fichier dans htdocs/blog_personnel/migrer.php
 * 2. Visitez : https://votre-site.infinityfreeapp.com/blog_personnel/migrer.php?token=VOTRE_TOKEN
 * 3. SUPPRIMEZ ce fichier immédiatement après usage.
 */

declare(strict_types=1);

const MIGRER_TOKEN = 'changez-moi-avant-upload';

if (($_GET['token'] ?? '') !== MIGRER_TOKEN) {
    http_response_code(403);
    exit('Accès refusé.');
}

define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$status = $kernel->call('migrate', ['--force' => true]);

header('Content-Type: text/plain; charset=utf-8');
echo "Migrations terminées (code {$status}).\n";
echo "SUPPRIMEZ migrer.php sur le serveur maintenant.\n";
