@extends('layouts.admin')

@section('title', 'Créer un article — Administration')
@section('titre-page', 'Nouvel article')
@section('sous-titre', 'Articles')

@section('contenu')
    <div class="mx-auto max-w-4xl admin-card p-6 lg:p-8">

        <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data">
            @csrf
            @php($labelBouton = 'Créer')
            @include('admin.articles._form')
        </form>
    </div>
@endsection
