@extends('adminlte::page')

@section('title_prefix', 'Solicitud de Firmas |')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('files.index') }}">Archivos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('files.show', $userFileId) }}">#{{ $userFileId }}</a></li>
                <li class="breadcrumb-item active">Solicitud de firmas #{{ $signatureRequestId }}</li>
                <li class="breadcrumb-item active">Detalles</li>
            </ol>
        </nav>

        <a href="{{ route('files.show', $userFileId) }}" class="btn btn-outline-secondary">
            <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
        </a>
    </div>
@endsection

@section('content')
    <livewire:UserFiles.Signatures.SignatureRequestShow :signatureRequestId="$signatureRequestId" />
@endsection
