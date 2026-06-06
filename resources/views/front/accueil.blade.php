@extends('layouts.application')

@section('body-class', 'page-accueil')

@section('title', 'Accueil — ' . config('app.name'))

@section('contenu')
    <div id="publications" class="home-layout">
        <section class="home-main">
            <header class="home-feed-header">
                <h1 class="home-feed-title">Derniers articles</h1>
            </header>

            @if ($articles->isEmpty())
                <p class="home-empty-state">
                    Aucun article publié pour le moment.
                </p>
            @else
                <div class="home-feed-list">
                    @foreach ($articles as $article)
                        @include('front._carte-article', ['article' => $article])
                    @endforeach
                </div>

                <div class="home-pagination">
                    {{ $articles->links() }}
                </div>
            @endif
        </section>

        @include('front.accueil._sidebar-portail')
    </div>
@endsection
