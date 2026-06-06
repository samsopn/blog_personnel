<aside class="home-sidebar" aria-label="Navigation et découverte">
    @include('front._barre-recherche', [
        'categories' => $categories,
        'termeRecherche' => '',
        'slugCategorieActive' => '',
    ])

    <div class="home-sidebar-widget">
        <h2 class="home-sidebar-title">Articles populaires</h2>
        <div class="home-sidebar-body">
            @if ($articlesPopulaires->isEmpty())
                <p class="home-sidebar-empty">Pas encore d'articles populaires.</p>
            @else
                <ol class="home-sidebar-list">
                    @foreach ($articlesPopulaires as $index => $articlePopulaire)
                        <li class="home-sidebar-list-item">
                            <span class="home-sidebar-rank">{{ $index + 1 }}</span>
                            <div>
                                <a href="{{ route('articles.show', $articlePopulaire->slug) }}" class="home-sidebar-link">
                                    {{ $articlePopulaire->title }}
                                </a>
                                <p class="home-sidebar-meta">{{ $articlePopulaire->views }} vues</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>

    <div class="home-sidebar-widget">
        <h2 class="home-sidebar-title">Explorer par catégorie</h2>
        <div class="home-sidebar-body">
            @if ($categories->isEmpty())
                <p class="home-sidebar-empty">Aucune catégorie disponible.</p>
            @else
                <ul class="home-category-chips">
                    @foreach ($categories as $categorie)
                        <li>
                            <a href="{{ route('categories.show', $categorie->slug) }}" class="home-category-chip">
                                <span>{{ $categorie->name }}</span>
                                <span class="home-category-count">{{ $categorie->articles_publies_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</aside>
