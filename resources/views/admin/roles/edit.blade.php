@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Editar Rol |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('root') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.roles.show', $roleId) }}">#{{ $roleId }}</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar rol</h1>
    <livewire:Admin.Roles.RoleEdit :roleId="$roleId" />
@endsection
