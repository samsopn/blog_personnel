<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleUtilisateur;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\InscriptionRequest;
use App\Models\Utilisateur;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InscriptionController extends Controller
{
    public function create(): View
    {
        return view('auth.inscription');
    }

    public function store(InscriptionRequest $request): RedirectResponse
    {
        $utilisateur = Utilisateur::create([
            'name' => $request->validated('name'),
            'username' => Utilisateur::normaliserUsername($request->validated('username')),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'role' => RoleUtilisateur::Utilisateur,
        ]);

        Auth::login($utilisateur);

        return redirect()
            ->route('accueil')
            ->with('succes', 'Bienvenue ! Votre compte a été créé.');
    }
}
