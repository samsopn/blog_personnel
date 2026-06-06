@extends('layouts.admin')

@section('title', 'Créer une catégorie — Administration')
@section('titre-page', 'Nouvelle catégorie')
@section('sous-titre', 'Catégories')

@section('contenu')
    <div class="mx-auto max-w-2xl admin-card p-6 lg:p-8">

        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            @php($labelBouton = 'Créer')
            @include('admin.categories._form')
        </form>
    </div>
@endsection
