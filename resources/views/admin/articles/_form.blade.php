@php
    $articleEdition = isset($article);
    $titre = old('title', $article->title ?? '');
    $contenu = old('content', $article->content ?? '');
    $statut = old('status', $articleEdition ? $article->status->value : 'published');
    $datePublication = old('published_at', isset($article) && $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '');
    $categoriesSelectionnees = collect(old('categories', $articleEdition ? $article->categories->pluck('id')->all() : []))->map(fn ($id) => (int) $id)->all();
    $tagsValeur = old('tags', $tags ?? '');
@endphp

<div class="space-y-5">
    <div>
        <label for="title" class="mb-1 block text-sm font-medium text-slate-700">Titre</label>
        <input id="title" name="title" type="text" value="{{ $titre }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
        @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="content" class="mb-1 block text-sm font-medium text-slate-700">Contenu</label>
        <textarea id="content" name="content" rows="10" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ $contenu }}</textarea>
        @error('content')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="status" class="mb-1 block text-sm font-medium text-slate-700">Statut</label>
            <select id="status" name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="draft" @selected($statut === 'draft')>Brouillon</option>
                <option value="published" @selected($statut === 'published')>Publié</option>
            </select>
            <p class="mt-1 text-xs text-slate-500">Seuls les articles <strong>publiés</strong> apparaissent sur la page d'accueil.</p>
            @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="published_at" class="mb-1 block text-sm font-medium text-slate-700">Date de publication <span class="font-normal text-slate-500">(optionnel)</span></label>
            <input id="published_at" name="published_at" type="datetime-local" value="{{ $datePublication }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            <p class="mt-1 text-xs text-slate-500">Laissez vide pour publier tout de suite. Une date future masque l'article jusqu'à ce moment.</p>
            @error('published_at')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="image" class="mb-1 block text-sm font-medium text-slate-700">Image principale <span class="font-normal text-slate-500">(optionnel — jpg, png ou webp, max 2 Mo)</span></label>
        <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        @if ($articleEdition && $article->image)
            <p class="mt-1 text-xs text-slate-500">Image actuelle :</p>
            <img src="{{ $article->urlImage() }}" alt="Image actuelle de l'article" class="mt-2 h-24 rounded-lg border border-slate-200 object-cover">
        @endif
        @error('image')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <p class="mb-2 text-sm font-medium text-slate-700">Catégories</p>
        <div class="grid gap-2 sm:grid-cols-2">
            @foreach ($categories as $categorie)
                <label class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm">
                    <input
                        type="checkbox"
                        name="categories[]"
                        value="{{ $categorie->id }}"
                        @checked(in_array($categorie->id, $categoriesSelectionnees, true))
                        class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                    >
                    <span>{{ $categorie->name }}</span>
                </label>
            @endforeach
        </div>
        @error('categories')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="tags" class="mb-1 block text-sm font-medium text-slate-700">Tags (séparés par des virgules)</label>
        <input id="tags" name="tags" type="text" value="{{ $tagsValeur }}" placeholder="laravel, php, mysql" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
        @error('tags')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-2">
    <a href="{{ route('admin.articles.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
        Annuler
    </a>
    <button type="submit" class="admin-btn-primary">
        {{ $labelBouton }}
    </button>
</div>
