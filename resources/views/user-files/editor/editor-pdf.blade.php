@extends('adminlte::page')

@section('title_prefix', 'Editor PDF |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('files.index') }}">Archivos</a></li>
            <li class="breadcrumb-item"><a href="{{ route('files.show', $userFile->id) }}">#{{ $userFile->id }}</a></li>
            <li class="breadcrumb-item active">Editor</li>
            <li class="breadcrumb-item active">PDF</li>
        </ol>
    </nav>
@endsection

@section('content')
    <livewire:UserFiles.Editor.PdfEditor :userFileId="$userFile->id" />
@endsection
