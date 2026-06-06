@extends('layouts.admin')

@section('title', 'Modifier un article — Administration')
@section('titre-page', 'Modifier l\'article')
@section('sous-titre', 'Articles')

@section('contenu')
    <div class="mx-auto max-w-4xl admin-card p-6 lg:p-8">

        <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @php($labelBouton = 'Enregistrer')
            @include('admin.articles._form')
        </form>
    </div>
@endsection
