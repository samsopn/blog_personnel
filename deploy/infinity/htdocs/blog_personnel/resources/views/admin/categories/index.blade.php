@extends('layouts.admin')

@section('title', 'Catégories — Administration')
@section('titre-page', 'Catégories')
@section('sous-titre', 'Organisation du contenu')

@section('contenu')
    <div class="admin-card p-6 lg:p-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-600">Gérez les thématiques de votre blog.</p>
            <a href="{{ route('admin.categories.create') }}" class="admin-btn-primary">+ Nouvelle catégorie</a>
        </div>

        @error('categorie')
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
        @enderror

        @if ($categories->isEmpty())
            <p class="rounded-xl bg-slate-50 px-4 py-8 text-center text-sm text-slate-600">Aucune catégorie pour le moment.</p>
        @else
            <div class="overflow-hidden rounded-xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Nom</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Slug</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($categories as $categorie)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $categorie->name }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $categorie->slug }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.categories.edit', $categorie) }}" class="admin-btn-secondary !px-3 !py-1.5 text-xs">Modifier</a>
                                        <form action="{{ route('admin.categories.destroy', $categorie) }}" method="POST" onsubmit="return confirm('Supprimer cette catégorie ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $categories->links() }}</div>
        @endif
    </div>
@endsection
