<?php

/**
 * Diagnostic session / CSRF — uploadez dans blog_personnel_2/test-session.php puis SUPPRIMEZ.
 */

declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');

$envPath = __DIR__.'/.env';

echo "=== Test session InfinityFree ===\n\n";

if (! is_file($envPath)) {
    exit("ERREUR : .env introuvable dans blog_personnel_2/\n");
}

$env = file_get_contents($envPath);

$checks = [
    'APP_KEY=base64:' => 'APP_KEY définie',
    'APP_URL=http' => 'APP_URL en http (OK pour free.nf)',
    'SESSION_DOMAIN=null' => 'PROBLEME : remplacez SESSION_DOMAIN=null par SESSION_DOMAIN= (vide)',
    'SESSION_SECURE_COOKIE=true' => 'ATTENTION : true sur HTTP peut causer 419 — mettez false',
];

foreach ($checks as $needle => $label) {
    $found = str_contains($env, $needle);
    if ($needle === 'APP_KEY=base64:') {
        echo ($found ? '[OK] ' : '[MANQUANT] ').$label."\n";
    } elseif ($needle === 'APP_URL=http') {
        echo ($found ? '[OK] ' : '[?] ').$label."\n";
    } else {
        echo ($found ? '[!!] ' : '[OK] ').$label."\n";
    }
}

echo "\n--- Test écriture session (fichiers) ---\n";
$dir = __DIR__.'/storage/framework/sessions';
if (! is_dir($dir)) {
    @mkdir($dir, 0755, true);
}
$testFile = $dir.'/test-'.uniqid().'.txt';
$writable = @file_put_contents($testFile, 'ok') !== false;
echo 'Dossier sessions inscriptible : '.($writable ? 'oui' : 'NON')."\n";
if ($writable) {
    @unlink($testFile);
}

echo "\n--- Table sessions (BDD) ---\n";
define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $exists = \Illuminate\Support\Facades\Schema::hasTable('sessions');
    echo 'Table sessions : '.($exists ? 'existe' : 'MANQUANTE — lancez migrer.php')."\n";
} catch (Throwable $e) {
    echo 'BDD : '.$e->getMessage()."\n";
}

echo "\nAprès correction .env, uploadez AppServiceProvider.php et lancez migrer.php\n";
