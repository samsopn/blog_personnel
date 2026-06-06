<form method="GET" action="{{ route('recherche') }}" class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <p class="mb-3 text-sm font-semibold text-slate-700">Recherche</p>

    <div class="space-y-3">
        <div>
            <label for="q" class="mb-1 block text-xs text-slate-600">Mot-clé</label>
            <input
                id="q"
                name="q"
                type="text"
                value="{{ $termeRecherche ?? request('q') }}"
                placeholder="Titre ou contenu..."
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
            >
        </div>

        <div>
            <label for="categorie" class="mb-1 block text-xs text-slate-600">Catégorie</label>
            <select id="categorie" name="categorie" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">Toutes</option>
                @foreach ($categories as $categorie)
                    <option value="{{ $categorie->slug }}" @selected(($slugCategorieActive ?? request('categorie')) === $categorie->slug)>
                        {{ $categorie->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            Rechercher
        </button>
    </div>
</form>
