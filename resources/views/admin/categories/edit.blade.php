@extends('layouts.admin')

@section('title', 'Modifier une catégorie — Administration')
@section('titre-page', 'Modifier la catégorie')
@section('sous-titre', 'Catégories')

@section('contenu')
    <div class="mx-auto max-w-2xl admin-card p-6 lg:p-8">

        <form method="POST" action="{{ route('admin.categories.update', $categorie) }}">
            @csrf
            @method('PUT')
            @php($labelBouton = 'Enregistrer')
            @include('admin.categories._form')
        </form>
    </div>
@endsection
