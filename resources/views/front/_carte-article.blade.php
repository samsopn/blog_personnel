<article class="admin-card p-5">
    @if ($article->urlImage())
        <a href="{{ route('articles.show', $article->slug) }}" class="article-card-image">
            <img src="{{ $article->urlImage() }}" alt="{{ $article->title }}">
        </a>
    @endif
    <h2 class="text-xl font-bold text-slate-900">
        <a href="{{ route('articles.show', $article->slug) }}" class="hover:text-brand-600">
            {{ $article->title }}
        </a>
    </h2>

    <p class="mt-2 text-sm text-slate-500">
        Publié {{ ($article->published_at ?? $article->created_at)->locale('fr')->diffForHumans() }}
        · {{ $article->views }} vues
    </p>

    <p class="mt-3 text-sm leading-relaxed text-slate-600">
        {{ \Illuminate\Support\Str::limit(strip_tags($article->content), 220) }}
    </p>

    <div class="mt-4 flex items-center gap-4 border-t border-slate-200 pt-3">
        @auth
            @php
                $aLike = $article->appreciations()->where('user_id', auth()->id())->exists();
                $aFavori = $article->favoris()->where('user_id', auth()->id())->exists();
            @endphp
            <form
                action="{{ route('articles.appreciations.toggle', $article) }}"
                method="POST"
                class="flex items-center gap-2"
                data-interaction-toggle="article-like"
                data-active="{{ $aLike ? '1' : '0' }}"
            >
                @csrf
                <x-interaction-like :active="$aLike" />
                <span class="text-sm font-medium text-slate-600" data-interaction-count>{{ $article->appreciations_count ?? 0 }}</span>
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
                <span class="text-sm font-medium text-slate-600" data-interaction-count>{{ $article->favoris_count ?? 0 }}</span>
            </form>
        @else
            <a href="{{ route('auth.connexion') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-rose-500" title="Connectez-vous pour liker">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
                <span class="text-sm font-medium">{{ $article->appreciations_count ?? 0 }}</span>
            </a>
            <a href="{{ route('auth.connexion') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-amber-500" title="Connectez-vous pour ajouter aux favoris">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885-4.725 2.885a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                </svg>
                <span class="text-sm font-medium">{{ $article->favoris_count ?? 0 }}</span>
            </a>
        @endauth

        <a href="{{ route('articles.show', $article->slug) }}#commentaires" class="inline-flex items-center gap-2 text-slate-500 hover:text-indigo-600" title="Voir et ajouter des commentaires">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75h6.75m-6.75 3h4.5m7.125-4.5c0 4.97-4.365 9-9.75 9a10.1 10.1 0 01-4.131-.87L3.75 17.25l.918-2.292a8.97 8.97 0 01-1.168-4.458c0-4.97 4.365-9 9.75-9s9.75 4.03 9.75 9z" />
            </svg>
            <span class="text-sm font-medium">{{ $article->commentaires_count ?? 0 }}</span>
        </a>
    </div>

    @if ($article->categories->isNotEmpty())
        <div class="mt-4 flex flex-wrap gap-2">
            @foreach ($article->categories as $categorie)
                <a href="{{ route('categories.show', $categorie->slug) }}" class="admin-badge bg-slate-100 text-slate-700 hover:bg-brand-50 hover:text-brand-700">
                    {{ $categorie->name }}
                </a>
            @endforeach
        </div>
    @endif
</article>
