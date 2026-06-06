@extends('layouts.application')

@section('title', 'Mon profil — ' . config('app.name'))

@section('contenu')
    <div class="mx-auto max-w-2xl rounded-xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
        <h1 class="mb-6 text-2xl font-bold text-slate-900">Mon profil</h1>

        <form method="POST" action="{{ route('user.profil.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="username" class="mb-1 block text-sm font-medium text-slate-700">Pseudo</label>
                <div class="flex items-center rounded-lg border border-slate-300 focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500">
                    <span class="pl-3 text-sm text-slate-500">@</span>
                    <input
                        id="username"
                        name="username"
                        type="text"
                        value="{{ old('username', $utilisateur->username) }}"
                        required
                        pattern="[a-zA-Z][a-zA-Z0-9_]{2,29}"
                        class="w-full border-0 bg-transparent py-2 pr-3 text-sm focus:outline-none focus:ring-0"
                    >
                </div>
                @error('username')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Nom complet</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name', $utilisateur->name) }}"
                    required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="avatar" class="mb-1 block text-sm font-medium text-slate-700">Photo de profil</label>
                @if ($utilisateur->urlAvatar())
                    <img src="{{ $utilisateur->urlAvatar() }}" alt="Avatar" class="mb-2 h-16 w-16 rounded-full border border-slate-200 object-cover">
                @endif
                <input id="avatar" name="avatar" type="file" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                @error('avatar')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <p class="mb-4 text-sm text-slate-500">Email : {{ $utilisateur->email }}</p>

            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Enregistrer
            </button>
        </form>
    </div>
@endsection
