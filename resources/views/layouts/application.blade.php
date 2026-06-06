<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="site-body min-h-screen flex flex-col font-sans text-slate-800 antialiased @yield('body-class')">
    <header class="site-header">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
            <a href="{{ route('accueil') }}" class="flex items-center gap-2">
                <span class="site-logo-icon">{{ strtoupper(substr(config('app.name'), 0, 1)) }}</span>
                <span class="site-logo-text">{{ config('app.name') }}</span>
            </a>

            <nav class="flex flex-wrap items-center justify-end gap-1 sm:gap-3 text-sm">
                <a href="{{ route('recherche') }}" class="site-nav-link" title="Rechercher" aria-label="Rechercher">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </a>
                @auth
                    <a href="{{ route('user.profil') }}" class="inline-flex items-center rounded-lg p-2 text-slate-600 hover:bg-slate-100 hover:text-brand-600 sm:p-0 sm:hover:bg-transparent" title="Profil" aria-label="Profil">
                        <svg class="h-5 w-5 sm:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <span class="hidden font-medium sm:inline">Profil</span>
                    </a>
                    <a href="{{ route('user.notifications') }}" class="relative inline-flex items-center rounded-lg p-2 text-slate-600 hover:bg-slate-100 hover:text-brand-600 sm:p-0 sm:hover:bg-transparent" title="Notifications" aria-label="Notifications">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        @if ($notificationsNonLues > 0)
                            <span class="absolute -right-0.5 -top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold leading-none text-white">
                                {{ $notificationsNonLues > 9 ? '9+' : $notificationsNonLues }}
                            </span>
                        @endif
                        <span class="hidden font-medium sm:inline">Notifications</span>
                    </a>
                    <a href="{{ route('user.favoris') }}" class="inline-flex items-center rounded-lg p-2 text-slate-600 hover:bg-slate-100 hover:text-brand-600 sm:p-0 sm:hover:bg-transparent" title="Favoris" aria-label="Favoris">
                        <svg class="h-5 w-5 sm:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                        <span class="hidden font-medium sm:inline">Favoris</span>
                    </a>
                    @if (auth()->user()->estAdministrateur())
                        <a href="{{ route('admin.dashboard') }}" class="admin-badge bg-brand-50 text-brand-700">Admin</a>
                    @endif
                    <form action="{{ route('auth.deconnexion') }}" method="POST">
                        @csrf
                        <button type="submit" class="font-medium text-slate-600 hover:text-slate-900">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('auth.connexion') }}" class="font-medium text-slate-600 hover:text-slate-900">Connexion</a>
                    <a href="{{ route('auth.inscription') }}" class="admin-btn-primary !py-2">S'inscrire</a>
                @endauth
            </nav>
        </div>
    </header>

    @if (session('succes'))
        <div class="mx-auto mt-4 max-w-6xl rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('succes') }}
        </div>
    @endif

    <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-8 lg:py-10">
        @yield('contenu')
    </main>

    @include('layouts._footer')
</body>
</html>
