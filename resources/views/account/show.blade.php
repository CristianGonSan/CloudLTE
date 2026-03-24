@extends('adminlte::page')

@section('title_prefix', 'Mi Cuenta |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('root') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Mi cuenta</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de mi cuenta</h1>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div>
                            <p class="font-semibold text-lg mb-0">{{ $user->name }}</p>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                        </div>
                    </div>
                    <hr>
                    @if ($user->roles->isNotEmpty())
                        @foreach ($user->roles()->orderBy('name')->get() as $role)
                            <span class="badge badge-info">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    @else
                        <span class="text-muted">Sin roles</span>
                    @endif
                </div>
            </div>

            <h2 class="h5">Cambiar contraseña</h2>
            <livewire:Account.ChangePassword />

            <h2 class="h5">Sesiones activas</h2>
            <livewire:Account.ShowSessions />
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Creado:</span>
                        <span>{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Actualizado:</span>
                        <span>{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
