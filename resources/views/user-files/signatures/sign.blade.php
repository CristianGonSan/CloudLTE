@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Firmar Archivo |')

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
            <li class="breadcrumb-item active">Firmar archivo</li>
        </ol>
    </nav>
@endsection

@section('content')
    <livewire:UserFiles.Signatures.PdfSigner :signatoryId="$signatoryId" />
@endsection
