@php
    $nomCategorie = old('name', $categorie->name ?? '');
@endphp

<div class="mb-4">
    <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Nom de la catégorie</label>
    <input
        id="name"
        name="name"
        type="text"
        value="{{ $nomCategorie }}"
        required
        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
    >
    @error('name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div class="flex items-center justify-end gap-2">
    <a href="{{ route('admin.categories.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
        Annuler
    </a>
    <button type="submit" class="admin-btn-primary">
        {{ $labelBouton }}
    </button>
</div>
