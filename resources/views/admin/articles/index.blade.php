@extends('layouts.admin')

@section('title', 'Articles — Administration')
@section('titre-page', 'Articles')
@section('sous-titre', 'Gestion du contenu')

@section('contenu')
    <div class="admin-card p-6 lg:p-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-600">Créez, publiez et modifiez vos articles.</p>
            <a href="{{ route('admin.articles.create') }}" class="admin-btn-primary">+ Nouvel article</a>
        </div>

        @if ($articles->isEmpty())
            <p class="rounded-xl bg-slate-50 px-4 py-8 text-center text-sm text-slate-600">Aucun article pour le moment.</p>
        @else
            <div class="overflow-hidden rounded-xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Titre</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Statut</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Catégories</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Vues</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($articles as $article)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3">
                                    <p class="font-medium text-slate-800">{{ $article->title }}</p>
                                    <p class="text-xs text-slate-500">{{ $article->slug }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($article->status->value === 'published')
                                        <span class="admin-badge bg-emerald-100 text-emerald-700">Publié</span>
                                    @else
                                        <span class="admin-badge bg-amber-100 text-amber-700">Brouillon</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $article->categories->pluck('name')->implode(', ') ?: '—' }}</td>
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $article->views }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.articles.edit', $article) }}" class="admin-btn-secondary !px-3 !py-1.5 text-xs">Modifier</a>
                                        <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Supprimer cet article ?');">
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
            <div class="mt-4">{{ $articles->links() }}</div>
        @endif
    </div>
@endsection
