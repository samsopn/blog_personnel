<?php

/**
 * Uploadez dans htdocs/blog_personnel_2/verifier-vendor.php puis ouvrez dans le navigateur.
 * SUPPRIMEZ après vérification.
 */

header('Content-Type: text/plain; charset=utf-8');

$base = __DIR__.'/vendor';

$fichiers = [
    'autoload.php',
    'composer/autoload_real.php',
    'symfony/deprecation-contracts/function.php',
    'laravel/framework/src/Illuminate/Foundation/Application.php',
];

echo "Verification vendor dans :\n{$base}\n\n";

$ok = true;

foreach ($fichiers as $fichier) {
    $chemin = $base.'/'.$fichier;
    $existe = is_file($chemin);
    echo ($existe ? '[OK] ' : '[MANQUANT] ').$fichier."\n";
    if (! $existe) {
        $ok = false;
    }
}

echo "\n";

if ($ok) {
    echo "vendor/ est COMPLET. Le site peut demarrer si .env et build/ sont OK.\n";
} else {
    echo "vendor/ est INCOMPLET. Supprimez vendor/, re-uploadez vendor.zip et extrayez entierement.\n";
}
