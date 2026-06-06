@extends('layouts.application')

@section('title', 'Mes favoris — ' . config('app.name'))

@section('contenu')
    <div class="rounded-xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
        <h1 class="mb-4 text-2xl font-bold text-slate-900">Mes favoris</h1>

        @if ($articles->isEmpty())
            <p class="text-sm text-slate-600">Vous n'avez pas encore d'articles en favori.</p>
        @else
            <div class="space-y-4">
                @foreach ($articles as $article)
                    @include('front._carte-article', ['article' => $article])
                @endforeach
            </div>

            <div class="mt-4">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
@endsection
