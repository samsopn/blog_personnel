@extends('layouts.application')

@section('title', 'Catégorie : ' . $categorie->name . ' — ' . config('app.name'))

@section('contenu')
    <div class="grid gap-8 lg:grid-cols-[2fr_1fr]">
        <section>
            <h1 class="mb-2 text-3xl font-bold text-slate-900">Catégorie : {{ $categorie->name }}</h1>
            <p class="mb-4 text-sm text-slate-600">
                {{ $articles->total() }} article(s) publié(s) dans cette catégorie.
            </p>

            @if ($articles->isEmpty())
                <p class="rounded-xl bg-white p-4 text-sm text-slate-600 shadow-sm ring-1 ring-slate-200">
                    Aucun article publié dans cette catégorie.
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
                'slugCategorieActive' => $categorie->slug,
            ])
        </aside>
    </div>
@endsection
