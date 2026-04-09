@extends('adminlte::page')

@section('title_prefix', 'Dashboard |')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Inicio</li>
            </ol>
        </nav>

        <button class="btn btn-outline-primary" onclick="openComponentFileUpload()">
            <i class="fas fa-fw fa-file-arrow-up mr-1"></i>Subir archivo
        </button>
    </div>
@endsection

@section('content')
    <livewire:UserFiles.UserFilesList />

    <livewire:UserFiles.UserFileUpload />

    <livewire:UserFiles.ModalFileShow />
@stop

@section('js')
    <script>
        function openComponentFileUpload() {
            Livewire.dispatch('openComponentUserFileUpload');
        }
    </script>
@endsection
