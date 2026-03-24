@extends('adminlte::page')

@section('title_prefix', 'Roles |')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('root') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Roles</li>
            </ol>
        </nav>

        @can('roles.create')
            <a href="{{ route('admin.roles.create') }}" class="btn btn-outline-primary">
                <i class="fas fa-fw fa-plus mr-1"></i>Nuevo rol
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <livewire:Admin.Roles.RolesTable />
@endsection
