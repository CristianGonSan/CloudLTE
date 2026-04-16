@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Solicitar Firmas |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('files.index') }}">Archivos</a></li>
            <li class="breadcrumb-item"><a href="{{ route('files.show', $userFileId) }}">#{{ $userFileId }}</a></li>
            <li class="breadcrumb-item active">Solicitar Firmas</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Crear solicitud de firmas</h1>
    <livewire:UserFiles.Signatures.SignatureRequestCreate :userFileId="$userFileId" />
@endsection
