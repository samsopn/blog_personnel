<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ConnexionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConnexionController extends Controller
{
    public function create(): View
    {
        return view('auth.connexion');
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

        $request->session()->regenerate();

        return redirect()->intended(route('accueil'));
    }
}
