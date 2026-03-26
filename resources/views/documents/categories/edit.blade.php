@extends('adminlte::page')

@section('title_prefix', 'Editar Categoria |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('document-categories.index') }}">Categorias</a></li>
            <li class="breadcrumb-item active">
                <a href="{{ route('document-categories.show', $categoryId) }}">#{{ $categoryId }}</a>
            </li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar categoría</h1>
    <livewire:Documents.Categories.CategoryEdit :categoryId="$categoryId" />
@endsection
