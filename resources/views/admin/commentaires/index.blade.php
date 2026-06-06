@extends('layouts.admin')

@section('title', 'Commentaires — Administration')
@section('titre-page', 'Commentaires')
@section('sous-titre', 'Modération')

@section('contenu')
    <div class="admin-card p-6 lg:p-8">
        <p class="mb-6 text-sm text-slate-600">Supprimez les commentaires inappropriés signalés ou non conformes.</p>

        @if ($commentaires->isEmpty())
            <p class="rounded-xl bg-slate-50 px-4 py-8 text-center text-sm text-slate-600">Aucun commentaire à modérer.</p>
        @else
            <div class="overflow-hidden rounded-xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Auteur</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Article</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Type</th>
                            <th class="px-4 py-3 text-left font-semibold text-slate-700">Contenu</th>
                            <th class="px-4 py-3 text-right font-semibold text-slate-700">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($commentaires as $commentaire)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $commentaire->auteur->name }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('articles.show', $commentaire->article->slug) }}" class="font-medium text-brand-600 hover:underline">
                                        {{ $commentaire->article->title }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($commentaire->parent_id)
                                        <span class="admin-badge bg-sky-100 text-sky-700">Réponse</span>
                                    @else
                                        <span class="admin-badge bg-slate-100 text-slate-700">Racine</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-700">{{ \Illuminate\Support\Str::limit($commentaire->body, 110) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('admin.commentaires.destroy', $commentaire) }}" onsubmit="return confirm('Supprimer ce commentaire ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $commentaires->links() }}</div>
        @endif
    </div>
@endsection
