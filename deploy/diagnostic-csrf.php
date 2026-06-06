<?php

/**
 * Diagnostic CSRF / session — uploadez dans htdocs/blog_personnel_2/diagnostic-csrf.php
 * http://sam-perso-blog2.free.nf/blog_personnel_2/diagnostic-csrf.php?token=VOTRE_TOKEN
 * SUPPRIMEZ après usage.
 */

declare(strict_types=1);

const DIAG_TOKEN = 'changez-moi-avant-upload';

if (($_GET['token'] ?? '') !== DIAG_TOKEN) {
    http_response_code(403);
    exit('Accès refusé. Changez DIAG_TOKEN dans diagnostic-csrf.php');
}

$base = __DIR__;

define('LARAVEL_START', microtime(true));
require $base.'/vendor/autoload.php';
$app = require $base.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create(
    $_SERVER['REQUEST_URI'] ?? '/diagnostic-csrf.php',
    $_SERVER['REQUEST_METHOD'] ?? 'GET',
    $_POST,
    $_COOKIE,
    [],
    $_SERVER
);

if ($request->isMethod('POST')) {
    header('Content-Type: text/plain; charset=utf-8');
    try {
        $kernel->handle($request);
        echo "OK : le jeton CSRF a été accepté. La session fonctionne.\n";
        echo "Si la connexion affiche encore 419, uploadez AppServiceProvider.php et corrigez APP_URL dans .env.\n";
    } catch (Illuminate\Session\TokenMismatchException) {
        http_response_code(419);
        echo "ECHEC : TokenMismatchException (même cause que « 419 Page Expired »).\n";
        echo "→ APP_URL en http:// si le site est en HTTP\n";
        echo "→ SESSION_SECURE_COOKIE=false\n";
        echo "→ SESSION_DOMAIN= (vide, pas « null »)\n";
        echo "→ Supprimez bootstrap/cache/*.php\n";
    } catch (Throwable $e) {
        echo 'Erreur : '.$e->getMessage()."\n";
    }
    $kernel->terminate($request, new Illuminate\Http\Response);
    exit;
}

$response = $kernel->handle($request);
$session = $app->make('session');
$token = $session->token();

header('Content-Type: text/html; charset=utf-8');

echo '<pre style="font:14px/1.5 monospace">';
echo "=== Diagnostic CSRF / session ===\n\n";
echo 'HTTP_HOST : '.htmlspecialchars($_SERVER['HTTP_HOST'] ?? '?')."\n";
echo 'HTTPS : '.htmlspecialchars($_SERVER['HTTPS'] ?? 'off')."\n";
echo 'X-Forwarded-Proto : '.htmlspecialchars($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '(absent)')."\n\n";
echo 'app.url : '.htmlspecialchars((string) config('app.url'))."\n";
echo 'session.driver : '.htmlspecialchars((string) config('session.driver'))."\n";
echo 'session.secure : '.(config('session.secure') ? 'true' : 'false')."\n";
echo 'session.domain : '.htmlspecialchars((string) (config('session.domain') ?? '(null)'))."\n";
echo 'request.isSecure() : '.($request->isSecure() ? 'true' : 'false')."\n";
echo 'session.id : '.htmlspecialchars($session->getId())."\n\n";

if (config('session.secure') && ! $request->isSecure() && ! str_starts_with((string) config('app.url'), 'https://')) {
    echo "⚠ session.secure=true alors que APP_URL n'est pas https — risque 419.\n";
}
if ($request->isSecure() && str_starts_with((string) config('app.url'), 'http://')) {
    echo "⚠ Le proxy indique HTTPS mais APP_URL est en http:// — mettez SESSION_SECURE_COOKIE=false.\n";
}

echo "\nSoumettez le bouton pour tester le POST CSRF :\n";
echo '</pre>';

echo '<form method="POST" action="?token='.htmlspecialchars(DIAG_TOKEN, ENT_QUOTES, 'UTF-8').'">';
echo '<input type="hidden" name="_token" value="'.htmlspecialchars($token, ENT_QUOTES, 'UTF-8').'">';
echo '<button type="submit">Tester POST CSRF</button></form>';

$kernel->terminate($request, $response);
