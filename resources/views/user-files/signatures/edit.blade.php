@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Editar Solicitud de Firmas |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('files.index') }}">Archivos</a></li>
            <li class="breadcrumb-item"><a href="{{ route('files.show', $userFileId) }}">#{{ $userFileId }}</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('files.signatures.show', [$userFileId, $signatureRequestId]) }}">
                    Solicitud de firmas #{{ $signatureRequestId }}
                </a>
            </li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar solicitud de firmas</h1>
    <livewire:UserFiles.Signatures.SignatureRequestEdit :signatureRequestId="$signatureRequestId" />
@endsection
