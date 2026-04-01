@extends('adminlte::page')

@section('title_prefix', 'Dashboard |')

@section('content_header')
    <h1 class="m-0">Dashboard</h1>
@stop

@section('content')
    <div class="mb-3">
        <button class="btn btn-outline-primary" onclick="openComponentFileUpload()">
            <i class="fas fa-fw fa-file-arrow-up mr-1"></i>Subir archivo
        </button>
    </div>

    <livewire:UserFiles.UserFilesList />

    <livewire:UserFiles.UserFileUpload />

    <livewire:UserFiles.ModalFileShow />
@stop

@push('js')
    <script>
        function openComponentFileUpload() {
            Livewire.dispatch('openComponentUserFileUpload');
        }
    </script>
@endpush
