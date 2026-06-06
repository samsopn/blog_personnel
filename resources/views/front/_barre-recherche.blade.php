<form method="GET" action="{{ route('recherche') }}" class="home-search-form">
    <p class="home-search-form-title">Recherche</p>

    <div class="home-search-form-body">
        <div>
            <label for="q" class="mb-1 block text-xs text-slate-600">Mot-clé</label>
            <input
                id="q"
                name="q"
                type="text"
                value="{{ $termeRecherche ?? request('q') }}"
                placeholder="Titre ou contenu..."
                class="w-full rounded-lg border border-brand-200 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500"
            >
        </div>

        <div>
            <label for="categorie" class="mb-1 block text-xs text-slate-600">Catégorie</label>
            <select id="categorie" name="categorie" class="w-full rounded-lg border border-brand-200 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500">
                <option value="">Toutes</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->slug }}" @selected(($slugCategorieActive ?? request('categorie')) === $categorie->slug)>
                        {{ $categorie->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit">Rechercher</button>
    </div>
</form>
