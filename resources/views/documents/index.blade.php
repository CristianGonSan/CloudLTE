@extends('adminlte::page')

@section('title_prefix', 'Mis Documentos |')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('root') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Mis Documentos</li>
            </ol>
        </nav>

        <a href="{{ route('documents.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-fw fa-plus mr-1"></i>Subir documento
        </a>
    </div>
@endsection

@section('content')
    <livewire:Documents.DocumentsTable />
@endsection
