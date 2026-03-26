@extends('adminlte::page')

@section('title_prefix', 'Categorias |')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('root') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Categorias</li>
            </ol>
        </nav>

        @can('categories.create')
            <a href="{{ route('document-categories.create') }}" class="btn btn-outline-primary">
                <i class="fas fa-fw fa-plus mr-1"></i>Nueva categoría
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <livewire:Documents.Categories.CategoriesTable />
@endsection
