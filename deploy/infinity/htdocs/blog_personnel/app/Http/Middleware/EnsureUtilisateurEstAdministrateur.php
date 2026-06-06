<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Vérifie que l'utilisateur connecté est administrateur.
 * Couche technique : réutilisable sur toute route /admin/* protégée.
 */
class EnsureUtilisateurEstAdministrateur
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->estAdministrateur()) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        return $next($request);
    }
}
