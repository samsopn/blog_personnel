<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">
        <a href="{{ route('accueil') }}" class="mb-8 text-xl font-semibold text-slate-900">
            {{ config('app.name') }}
        </a>

        @if (session('succes'))
            <div class="mb-4 w-full max-w-md rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('succes') }}
            </div>
        @endif

        <main class="w-full max-w-md rounded-xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            @yield('contenu')
        </main>
    </div>
</body>
</html>
