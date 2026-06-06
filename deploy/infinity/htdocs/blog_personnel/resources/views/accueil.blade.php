@extends('layouts.application')

@section('title', 'Accueil — ' . config('app.name'))

@section('contenu')
    <div class="rounded-xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
        <h1 class="text-3xl font-bold text-slate-900">Bienvenue dans mon blog</h1>
        <p class="mt-3 text-slate-600">
            Phase 1 terminée : l'authentification est en place. Les articles seront affichés ici en Phase 3.
        </p>

        @auth
            <p class="mt-4 text-sm text-slate-500">
                Connecté en tant que <strong>{{ auth()->user()->name }}</strong>
                ({{ auth()->user()->role->value }}).
            </p>
        @else
            <p class="mt-6">
                <a href="{{ route('auth.inscription') }}" class="font-medium text-indigo-600 hover:underline">
                    Créer un compte
                </a>
                pour commenter, liker et sauvegarder des articles.
            </p>
        @endauth
    </div>
@endsection
