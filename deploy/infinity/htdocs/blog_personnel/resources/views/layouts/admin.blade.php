<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-30 hidden w-64 flex-col bg-slate-800 lg:flex">
            <div class="border-b border-slate-700 px-6 py-6">
                <a href="{{ route('admin.dashboard') }}" class="block">
                    <p class="text-lg font-bold text-slate-100">{{ config('app.name') }}</p>
                </a>
            </div>

            <nav class="flex-1 space-y-1 px-4 py-6">
                <a href="{{ route('admin.dashboard') }}" @class([request()->routeIs('admin.dashboard') ? 'admin-nav-link-active' : 'admin-nav-link-idle'])>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-700 text-slate-300">⌂</span>
                    Dashboard
                </a>
                <a href="{{ route('admin.articles.index') }}" @class([request()->routeIs('admin.articles.*') ? 'admin-nav-link-active' : 'admin-nav-link-idle'])>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-700 text-slate-300">✎</span>
                    Articles
                </a>
                <a href="{{ route('admin.categories.index') }}" @class([request()->routeIs('admin.categories.*') ? 'admin-nav-link-active' : 'admin-nav-link-idle'])>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-700 text-slate-300">☰</span>
                    Catégories
                </a>
                <a href="{{ route('admin.commentaires.index') }}" @class([request()->routeIs('admin.commentaires.*') ? 'admin-nav-link-active' : 'admin-nav-link-idle'])>
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-700 text-slate-300">💬</span>
                    Commentaires
                </a>
            </nav>

            <div class="border-t border-slate-700 px-4 py-5">
                <a href="{{ route('accueil') }}" class="admin-nav-link-idle mb-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-700 text-slate-300">↗</span>
                    Voir le blog
                </a>
                <form action="{{ route('auth.deconnexion') }}" method="POST">
                    @csrf
                    <button type="submit" class="admin-nav-link-idle w-full text-left">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-700 text-slate-300">⎋</span>
                        Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        {{-- Contenu --}}
        <div class="flex min-h-screen flex-1 flex-col lg:pl-64">
            {{-- Topbar mobile + desktop --}}
            <header class="sticky top-0 z-20 border-b border-slate-200/80 bg-white/90 backdrop-blur">
                <div class="flex items-center justify-between px-4 py-4 lg:px-8">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">@yield('sous-titre', 'Tableau de bord')</p>
                        <h1 class="text-xl font-bold text-slate-900">@yield('titre-page', config('app.name'))</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <p class="hidden text-sm font-semibold text-slate-800 sm:block">{{ auth()->user()->name }}</p>
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-600 text-sm font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>

                {{-- Nav mobile --}}
                <div class="flex gap-2 overflow-x-auto border-t border-slate-100 px-4 py-2 lg:hidden">
                    <a href="{{ route('admin.dashboard') }}" class="shrink-0 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700">Dashboard</a>
                    <a href="{{ route('admin.articles.index') }}" class="shrink-0 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700">Articles</a>
                    <a href="{{ route('admin.categories.index') }}" class="shrink-0 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700">Catégories</a>
                    <a href="{{ route('admin.commentaires.index') }}" class="shrink-0 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700">Commentaires</a>
                </div>
            </header>

            @if (session('succes'))
                <div class="mx-4 mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 lg:mx-8">
                    {{ session('succes') }}
                </div>
            @endif

            <main class="flex-1 px-4 py-6 lg:px-8 lg:py-8">
                @yield('contenu')
            </main>
        </div>
    </div>
</body>
</html>
