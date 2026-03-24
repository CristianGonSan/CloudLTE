@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Nuevo Rol |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('root') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a></li>
            <li class="breadcrumb-item active">Nuevo</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Nuevo rol</h1>
    <livewire:Admin.Roles.RoleCreate />
@endsection
