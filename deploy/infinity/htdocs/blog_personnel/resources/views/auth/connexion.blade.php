@extends('layouts.invite')

@section('title', 'Connexion — ' . config('app.name'))

@section('contenu')
    <h1 class="mb-6 text-2xl font-bold text-slate-900">Connexion</h1>

    @error('email')
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $message }}
        </div>
    @enderror

    <form method="POST" action="{{ route('auth.connexion') }}" class="space-y-1">
        @csrf

        <x-champ-formulaire label="Adresse email" name="email" type="email" required />

        <x-champ-formulaire label="Mot de passe" name="password" type="password" required />

        <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
            Se connecter
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-600">
        Pas encore de compte ?
        <a href="{{ route('auth.inscription') }}" class="font-medium text-indigo-600 hover:underline">S'inscrire</a>
    </p>
@endsection
