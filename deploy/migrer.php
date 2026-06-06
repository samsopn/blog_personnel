<?php

/**
 * Script temporaire InfinityFree (sans SSH).
 *
 * 1. Changez MIGRER_TOKEN ci-dessous
 * 2. Uploadez dans htdocs/blog_personnel_2/migrer.php
 * 3. Visitez : https://VOTRE-SITE.infinityfreeapp.com/blog_personnel_2/migrer.php?token=VOTRE_TOKEN
 * 4. SUPPRIMEZ ce fichier immédiatement
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

header('Content-Type: text/plain; charset=utf-8');

$kernel->call('config:clear');
echo "config:clear OK\n";

$kernel->call('cache:clear');
echo "cache:clear OK\n";

$kernel->call('migrate', ['--force' => true]);
echo "migrate OK\n";

echo "\nVérifiez le .env sur le serveur :\n";
echo "- APP_URL=http://sam-perso-blog.free.nf (sans slash final)\n";
echo "- SESSION_DOMAIN= (ligne vide, pas null)\n";
echo "- SESSION_SECURE_COOKIE=false\n";
echo "- APP_KEY=base64:...\n";
echo "\nSUPPRIMEZ migrer.php maintenant.\n";
