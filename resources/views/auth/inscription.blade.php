@extends('layouts.invite')

@section('title', 'Inscription — ' . config('app.name'))

@section('contenu')
    <h1 class="mb-6 text-2xl font-bold text-slate-900">Créer un compte</h1>

    <form method="POST" action="{{ route('auth.inscription.store') }}" class="space-y-1">
        @csrf

        <x-champ-formulaire label="Nom complet" name="name" required />

        <div class="mb-4">
            <label for="username" class="mb-1 block text-sm font-medium text-slate-700">Pseudo</label>
            <div class="flex items-center rounded-lg border border-slate-300 focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500">
                <span class="pl-3 text-sm text-slate-500">@</span>
                <input
                    id="username"
                    name="username"
                    type="text"
                    value="{{ old('username') }}"
                    required
                    pattern="[a-zA-Z][a-zA-Z0-9_]{2,29}"
                    class="w-full border-0 bg-transparent py-2 pr-3 text-sm focus:outline-none focus:ring-0"
                    placeholder="samson"
                >
            </div>
            <p class="mt-1 text-xs text-slate-500">Lettres, chiffres et _ uniquement (3 à 30 caractères).</p>
            @error('username')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <x-champ-formulaire label="Adresse email" name="email" type="email" required />

        <x-champ-formulaire label="Mot de passe" name="password" type="password" required />

        <x-champ-formulaire label="Confirmer le mot de passe" name="password_confirmation" type="password" required />

        <button type="submit" class="mt-4 w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
            S'inscrire
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-600">
        Déjà un compte ?
        <a href="{{ route('auth.connexion') }}" class="font-medium text-indigo-600 hover:underline">Se connecter</a>
    </p>
@endsection
