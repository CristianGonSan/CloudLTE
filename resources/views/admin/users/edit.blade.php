@extends('adminlte::page')

@section('plugins.Select2', true)

@section('title_prefix', 'Editar Usuario |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('root') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuarios</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.show', $userId) }}">#{{ $userId }}</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar usuario</h1>
    <livewire:Admin.Users.UserEdit :userId="$userId" />
@endsection
