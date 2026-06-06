<?php

/**
 * Corrige l'erreur 419 sur InfinityFree.
 *
 * 1. Changez CORRIGER_TOKEN ci-dessous
 * 2. Uploadez dans htdocs/blog_personnel_2/corriger-419.php
 * 3. Ouvrez : https://sam-perso-blog2.free.nf/blog_personnel_2/corriger-419.php?token=VOTRE_TOKEN&https=1
 * 4. SUPPRIMEZ ce fichier après usage
 */

declare(strict_types=1);

// Mettez la MÊME valeur dans l'URL : ?token=mon-secret-123
const CORRIGER_TOKEN = 'mon-secret-123';

if (($_GET['token'] ?? '') !== CORRIGER_TOKEN) {
    http_response_code(403);
    exit('Accès refusé. Changez CORRIGER_TOKEN dans corriger-419.php');
}

header('Content-Type: text/plain; charset=utf-8');

echo "=== Correction erreur 419 ===\n\n";

$base = __DIR__;

// 1. Supprimer le cache de config (souvent la cause sur InfinityFree)
$cacheDir = $base.'/bootstrap/cache';
foreach (glob($cacheDir.'/*.php') ?: [] as $fichier) {
    if (basename($fichier) === '.gitignore') {
        continue;
    }
    @unlink($fichier);
    echo "Supprimé : bootstrap/cache/".basename($fichier)."\n";
}

// 2. Dossiers sessions inscriptibles
foreach ([
    $base.'/storage/framework/sessions',
    $base.'/storage/framework/cache',
    $base.'/storage/framework/views',
] as $dossier) {
    if (! is_dir($dossier)) {
        @mkdir($dossier, 0755, true);
    }
    @chmod($dossier, 0755);
    echo 'Dossier OK : '.$dossier."\n";
}

// 3. Vérifier .env
$envPath = $base.'/.env';
if (! is_file($envPath)) {
    exit("\nERREUR : fichier .env manquant dans blog_personnel_2/\n");
}

$env = file_get_contents($envPath);
$problemes = [];

if (! str_contains($env, 'APP_KEY=base64:')) {
    $problemes[] = 'APP_KEY manquante (copiez-la depuis votre PC : php artisan key:generate --show)';
}
if (str_contains($env, 'SESSION_DOMAIN=null')) {
    $problemes[] = 'Remplacez SESSION_DOMAIN=null par SESSION_DOMAIN= (ligne vide)';
}
if (str_contains($env, 'SESSION_SECURE_COOKIE=true') && str_contains($env, 'APP_URL=http://')) {
    $problemes[] = 'Mettez SESSION_SECURE_COOKIE=false ou passez APP_URL en https://';
}
if (str_contains($env, 'SESSION_SECURE_COOKIE=false') && str_contains($env, 'APP_URL=https://')) {
    $problemes[] = 'En HTTPS : supprimez SESSION_SECURE_COOKIE=false (laissez la ligne vide)';
}
if (str_contains($env, 'APP_URL=http://127.0.0.1') || str_contains($env, 'APP_URL=http://localhost')) {
    $problemes[] = 'APP_URL doit être https://sam-perso-blog2.free.nf';
}
if (isset($_GET['https']) && str_contains($env, 'APP_URL=http://')) {
    $problemes[] = 'Relancez avec ?https=1 pour passer APP_URL en https:// automatiquement';
}

$corrige = false;
if (str_contains($env, 'SESSION_DOMAIN=null')) {
    $env = str_replace('SESSION_DOMAIN=null', 'SESSION_DOMAIN=', $env);
    $corrige = true;
}
if (str_contains($env, 'SESSION_SECURE_COOKIE=true') && str_contains($env, 'APP_URL=http')) {
    $env = preg_replace('/SESSION_SECURE_COOKIE=true/', 'SESSION_SECURE_COOKIE=false', $env) ?? $env;
    $corrige = true;
}
if (! str_contains($env, 'SESSION_DRIVER=file')) {
    $env = preg_replace('/SESSION_DRIVER=\w+/', 'SESSION_DRIVER=file', $env, 1) ?? $env;
    if (! str_contains($env, 'SESSION_DRIVER=file')) {
        $env .= "\nSESSION_DRIVER=file\n";
    }
    $corrige = true;
}
if (isset($_GET['https'])) {
    if (str_contains($env, 'APP_URL=http://')) {
        $env = preg_replace('/APP_URL=http:\/\//', 'APP_URL=https://', $env, 1) ?? $env;
        $corrige = true;
        echo "APP_URL passé en https://\n";
    }
    if (str_contains($env, 'SESSION_SECURE_COOKIE=false')) {
        $env = preg_replace('/SESSION_SECURE_COOKIE=false/', 'SESSION_SECURE_COOKIE=', $env, 1) ?? $env;
        $corrige = true;
    }
}
if ($corrige) {
    file_put_contents($envPath, $env);
    echo ".env : corrections automatiques écrites (domaine, secure, driver).\n";
    $problemes = array_filter($problemes, fn ($p) => ! str_contains($p, 'SESSION_DOMAIN') && ! str_contains($p, 'SESSION_SECURE'));
}

if ($problemes !== []) {
    echo "\n--- Corrigez le .env manuellement ---\n";
    foreach ($problemes as $p) {
        echo "• {$p}\n";
    }
    echo "\nExemple :\n";
    echo "APP_URL=https://sam-perso-blog2.free.nf\n";
    echo "SESSION_DOMAIN=\n";
    echo "SESSION_SECURE_COOKIE=\n";
    echo "SESSION_DRIVER=file\n";
    if (str_contains($env, 'APP_KEY=base64:') === false) {
        exit;
    }
}

echo "\n.env : ".($corrige ? 'corrigé automatiquement' : 'OK')."\n";

// 4. Laravel : migrations + cache
define('LARAVEL_START', microtime(true));
require $base.'/vendor/autoload.php';
$app = require $base.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kernel->call('config:clear');
echo "config:clear OK\n";

$provider = $app->getProvider(\App\Providers\AppServiceProvider::class);
if ($provider) {
    $provider->boot();
}
echo 'session.driver : '.config('session.driver')."\n";
echo 'session.secure : '.(config('session.secure') ? 'true' : 'false')."\n";
echo 'session.domain : '.(config('session.domain') ?? '(null)')."\n";

$kernel->call('cache:clear');
echo "cache:clear OK\n";

try {
    $kernel->call('migrate', ['--force' => true]);
    echo "migrate OK\n";
} catch (Throwable $e) {
    echo "migrate : ".$e->getMessage()."\n";
}

echo "\n=== Terminé ===\n";
echo "1. Supprimez corriger-419.php sur le serveur\n";
echo "2. Videz les cookies du site (ou navigation privée)\n";
echo "3. Réessayez : https://sam-perso-blog2.free.nf/auth/connexion\n";
echo "4. Uploadez .htaccess à la racine htdocs/ (redirection HTTPS)\n";
