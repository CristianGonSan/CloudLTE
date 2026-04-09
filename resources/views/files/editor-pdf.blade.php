@extends('adminlte::page')

@section('title_prefix', 'Editor PDF |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('root') }}">Inicio</a></li>
            <li class="breadcrumb-item">Archivos</li>
            <li class="breadcrumb-item">#{{ $userFile->id }}</li>
            <li class="breadcrumb-item active">Editor PDF</li>
        </ol>
    </nav>
@endsection

@section('content')
    <livewire:Files.FileEditorPdf :userFileId="$userFile->id" />
@endsection
