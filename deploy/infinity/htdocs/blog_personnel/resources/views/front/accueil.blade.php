@extends('layouts.application')

@section('title', 'Accueil — ' . config('app.name'))

@section('contenu')
    <div class="grid gap-8 lg:grid-cols-[2fr_1fr]">
        <section>
            <h1 class="mb-2 text-3xl font-bold text-slate-900">Bienvenue dans mon blog</h1>
            <p class="mb-4 text-sm font-medium text-slate-600">Derniers articles publiés</p>

            @if ($articles->isEmpty())
                <p class="rounded-xl bg-white p-4 text-sm text-slate-600 shadow-sm ring-1 ring-slate-200">
                    Aucun article publié pour le moment.
                </p>
            @else
                <div class="space-y-4">
                    @foreach ($articles as $article)
                        @include('front._carte-article', ['article' => $article])
                    @endforeach
                </div>

                <div class="mt-5">
                    {{ $articles->links() }}
                </div>
            @endif
        </section>

        <aside class="space-y-4">
            @include('front._barre-recherche', [
                'categories' => $categories,
                'termeRecherche' => '',
                'slugCategorieActive' => '',
            ])

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

            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="mb-3 text-sm font-semibold text-slate-700">Catégories</h2>
                @if ($categories->isEmpty())
                    <p class="text-sm text-slate-600">Aucune catégorie disponible.</p>
                @else
                    <ul class="space-y-2 text-sm">
                        @foreach ($categories as $categorie)
                            <li class="flex items-center justify-between">
                                <a href="{{ route('categories.show', $categorie->slug) }}" class="text-slate-700 hover:text-indigo-700">
                                    {{ $categorie->name }}
                                </a>
                                <span class="text-xs text-slate-500">{{ $categorie->articles_publies_count }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </aside>
    </div>
@endsection
