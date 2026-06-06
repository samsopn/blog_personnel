@extends('layouts.application')

@section('title', $article->title . ' — ' . config('app.name'))

@section('contenu')
    <div class="grid gap-8 lg:grid-cols-[2fr_1fr]">
        <article class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-3xl font-bold text-slate-900">{{ $article->title }}</h1>

            <p class="mt-2 text-sm text-slate-500">
                Publié {{ ($article->published_at ?? $article->created_at)->locale('fr')->diffForHumans() }}
                · {{ $article->views }} vues
            </p>

            @if ($article->urlImage())
                <div class="article-image">
                    <img src="{{ $article->urlImage() }}" alt="{{ $article->title }}">
                </div>
            @endif

            @if ($article->categories->isNotEmpty() || $article->etiquettes->isNotEmpty())
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($article->categories as $categorie)
                        <a href="{{ route('categories.show', $categorie->slug) }}" class="rounded-full bg-slate-100 px-2 py-1 text-xs text-slate-700 hover:bg-slate-200">
                            {{ $categorie->name }}
                        </a>
                    @endforeach
                    @foreach ($article->etiquettes as $etiquette)
                        <span class="rounded-full bg-indigo-50 px-2 py-1 text-xs text-indigo-700">#{{ $etiquette->name }}</span>
                    @endforeach
                </div>
            @endif

            <div class="article-content">
                {!! e($article->content) !!}
            </div>

            <div id="interactions" class="mt-6 flex flex-wrap items-center gap-4 border-t border-slate-200 pt-4">
                @auth
                    <form
                        action="{{ route('articles.appreciations.toggle', $article) }}"
                        method="POST"
                        class="flex items-center gap-2"
                        data-interaction-toggle="article-like"
                        data-active="{{ $aLike ? '1' : '0' }}"
                    >
                        @csrf
                        <x-interaction-like :active="$aLike" />
                        <span class="text-sm font-medium text-slate-600" data-interaction-count>{{ $article->appreciations_count }}</span>
                    </form>

                    <form
                        action="{{ route('articles.favoris.toggle', $article) }}"
                        method="POST"
                        class="flex items-center gap-2"
                        data-interaction-toggle="article-favori"
                        data-active="{{ $aFavori ? '1' : '0' }}"
                    >
                        @csrf
                        <x-interaction-favori :active="$aFavori" />
                        <span class="text-sm font-medium text-slate-600" data-interaction-count>{{ $article->favoris_count }}</span>
                    </form>
                @else
                    <div class="flex items-center gap-2 text-slate-400" title="Likes">
                        <span class="inline-flex rounded-full border border-slate-200 p-2.5">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </span>
                        <span class="text-sm font-medium">{{ $article->appreciations_count }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-400" title="Favoris">
                        <span class="inline-flex rounded-full border border-slate-200 p-2.5">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885-4.725 2.885a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg>
                        </span>
                        <span class="text-sm font-medium">{{ $article->favoris_count }}</span>
                    </div>
                    <a href="{{ route('auth.connexion') }}" class="text-sm text-indigo-600 hover:underline">Connectez-vous pour interagir</a>
                @endauth
            </div>

            @include('front._commentaires', ['article' => $article])
        </article>

        <aside class="space-y-4">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="mb-3 text-sm font-semibold text-slate-700">Articles populaires</h2>
                @if ($articlesPopulaires->isEmpty())
                    <p class="text-sm text-slate-600">Pas encore d'articles populaires.</p>
                @else
                    <ul class="space-y-2 text-sm">
                        @foreach ($articlesPopulaires as $articlePopulaire)
                            <li>
                                <a href="{{ route('articles.show', $articlePopulaire->slug) }}" class="text-slate-700 hover:text-indigo-700">
                                    {{ $articlePopulaire->title }}
                                </a>
                                <p class="text-xs text-slate-500">{{ $articlePopulaire->views }} vues</p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </aside>
    </div>
@endsection
