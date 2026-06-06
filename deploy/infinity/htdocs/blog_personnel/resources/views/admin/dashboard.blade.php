@extends('layouts.admin')

@section('title', 'Dashboard — ' . config('app.name'))
@section('titre-page', 'Tableau de bord')
@section('sous-titre', 'Vue d\'ensemble')

@section('contenu')
    <div class="space-y-8">
        <section class="admin-card overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50 p-6 lg:p-8">
                <p class="text-sm font-medium text-slate-600">Bonjour, {{ $utilisateur->name }}</p>
                <h2 class="mt-1 text-2xl font-bold text-slate-900 lg:text-3xl">Pilotez votre blog en un coup d'œil</h2>
                <p class="mt-2 max-w-xl text-sm text-slate-600">
                    Contenu, modération et engagement lecteurs — tout est centralisé ici.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('admin.articles.create') }}" class="admin-btn-primary">+ Nouvel article</a>
                    <a href="{{ route('admin.categories.create') }}" class="admin-btn-secondary">+ Catégorie</a>
                </div>
            </div>
        </section>

        <section>
            <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-slate-500">Actions rapides</h2>
            <div class="grid gap-4 md:grid-cols-3">
                <x-admin.action-card title="Articles" description="Créer, publier ou mettre en brouillon." :href="route('admin.articles.index')" button="Gérer" icon="✎" />
                <x-admin.action-card title="Catégories" description="Organiser le contenu par thème." :href="route('admin.categories.index')" button="Gérer" icon="☰" />
                <x-admin.action-card title="Modération" description="Supprimer les commentaires inappropriés." :href="route('admin.commentaires.index')" button="Modérer" icon="💬" />
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <div class="admin-card p-6">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="font-bold text-slate-900">Derniers articles</h2>
                    <a href="{{ route('admin.articles.index') }}" class="text-sm font-semibold text-slate-700 hover:underline">Tout voir</a>
                </div>
                @forelse ($derniersArticles as $article)
                    <div @class(['flex items-center justify-between gap-3 border-b border-slate-100 py-3 last:border-0'])>
                        <div class="min-w-0">
                            <p class="truncate font-medium text-slate-800">{{ $article->title }}</p>
                            <p class="text-xs text-slate-500">{{ $article->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @if ($article->status->value === 'published')
                            <span class="admin-badge bg-emerald-100 text-emerald-700">Publié</span>
                        @else
                            <span class="admin-badge bg-amber-100 text-amber-700">Brouillon</span>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun article.</p>
                @endforelse
            </div>

            <div class="admin-card p-6">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="font-bold text-slate-900">Derniers commentaires</h2>
                    <a href="{{ route('admin.commentaires.index') }}" class="text-sm font-semibold text-slate-700 hover:underline">Modérer</a>
                </div>
                @forelse ($derniersCommentaires as $commentaire)
                    <div class="border-b border-slate-100 py-3 last:border-0">
                        <p class="text-sm font-medium text-slate-800">{{ $commentaire->auteur->name }}</p>
                        <p class="text-xs text-slate-500">{{ $commentaire->article->title }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($commentaire->body, 90) }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun commentaire.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
