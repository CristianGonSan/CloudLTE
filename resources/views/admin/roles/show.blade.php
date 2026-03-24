@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Rol |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('root') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
            <li class="breadcrumb-item active">#{{ $roleId }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de rol</h1>
    <livewire:Admin.Roles.RoleShow :roleId="$roleId" />

    <hr class="mt-1">

    <h1 class="h4">Usuarios con este rol</h1>
    <livewire:Admin.Roles.UsersTable :roleId="$roleId" />
@endsection
