<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfilRequest;
use App\Models\Utilisateur;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function edit(): View
    {
        return view('user.profil', [
            'utilisateur' => auth()->user(),
        ]);
    }

    public function update(ProfilRequest $request): RedirectResponse
    {
        $utilisateur = $request->user();
        $utilisateur->name = $request->validated('name');
        $utilisateur->username = Utilisateur::normaliserUsername($request->validated('username'));

        if ($request->hasFile('avatar')) {
            if ($utilisateur->avatar) {
                Storage::disk('public')->delete($utilisateur->avatar);
            }

            $utilisateur->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $utilisateur->save();

        return redirect()
            ->route('user.profil')
            ->with('succes', 'Profil mis à jour avec succès.');
    }
}
