@extends('adminlte::page')

@section('title_prefix', 'Nueva Categoria |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('root') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('document-categories.index') }}">Categorias</a></li>
            <li class="breadcrumb-item active">Nueva</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Nueva categoría</h1>
    <livewire:Documents.Categories.CategoryCreate />
@endsection
