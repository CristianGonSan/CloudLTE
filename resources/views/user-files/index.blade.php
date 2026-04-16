@extends('adminlte::page')

@section('title_prefix', 'Archivos |')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Archivos</li>
            </ol>
        </nav>

        <div>
            <button class="btn btn-outline-primary mr-1" onclick="openSignaturesModal()">
                <i class="fas fa-fw fa-signature mr-1"></i>Mis firmas
            </button>

            <button class="btn btn-outline-primary" onclick="openComponentFileUpload()">
                <i class="fas fa-fw fa-file-arrow-up mr-1"></i>Subir archivo
            </button>
        </div>
    </div>
@endsection

@section('content')
    <livewire:UserFiles.UserFilesList />

    <livewire:UserFiles.UserFileUpload />

    <livewire:UserFiles.Signatures.SignaturesModal />
@stop

@section('js')
    <script>
        function openComponentFileUpload() {
            Livewire.dispatch('openComponentUserFileUpload');
        }

        function openSignaturesModal() {
            Livewire.dispatch('openSignaturesModal');
        }
    </script>
@endsection
