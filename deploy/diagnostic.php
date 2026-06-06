<?php

/**
 * Uploadez dans htdocs/diagnostic.php, ouvrez dans le navigateur, puis SUPPRIMEZ.
 */

header('Content-Type: text/plain; charset=utf-8');

$htdocs = __DIR__;

echo "=== Diagnostic InfinityFree ===\n\n";
echo "htdocs : {$htdocs}\n\n";

foreach (['blog_personnel_2', 'blog_personnel'] as $dossier) {
    $racine = $htdocs.'/'.$dossier;
    echo "--- {$dossier} ---\n";
    echo 'Existe : '.(is_dir($racine) ? 'oui' : 'NON')."\n";
    echo 'vendor/autoload.php : '.(is_file($racine.'/vendor/autoload.php') ? 'OK' : 'MANQUANT')."\n";
    echo 'bootstrap/app.php : '.(is_file($racine.'/bootstrap/app.php') ? 'OK' : 'MANQUANT')."\n";
    echo '.env : '.(is_file($racine.'/.env') ? 'OK' : 'MANQUANT')."\n\n";
}

echo "--- Racine htdocs ---\n";
echo 'index.php : '.(is_file($htdocs.'/index.php') ? 'OK' : 'MANQUANT')."\n";
echo 'build/manifest.json : '.(is_file($htdocs.'/build/manifest.json') ? 'OK' : 'MANQUANT')."\n";
echo 'storage/ : '.(is_dir($htdocs.'/storage') ? 'oui' : 'non')."\n";
