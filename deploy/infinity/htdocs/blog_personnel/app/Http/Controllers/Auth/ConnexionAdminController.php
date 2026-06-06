<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ConnexionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Connexion réservée à l'espace administrateur (route dédiée).
 */
class ConnexionAdminController extends Controller
{
    public function create(): View
    {
        return view('admin.connexion');
    }

    public function store(ConnexionRequest $request): RedirectResponse
    {
        if (! Auth::attempt(
            $request->only('email', 'password'),
            false
        )) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Identifiants incorrects.']);
        }

        if (! Auth::user()->estAdministrateur()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Ce compte n\'a pas accès à l\'administration.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }
}
