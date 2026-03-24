<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Nombre</dt>
                    <dd class="col-sm-8 font-weight-bold">{{ $role->name }}</dd>

                    <dt class="col-sm-4 text-muted">Permisos</dt>
                    <dd class="col-sm-8 mb-0">
                        <a href="#permisos-collapse" data-toggle="collapse" class="text-decoration-none">
                            {{ $role->permissions()->count() }} permisos asignados
                            <i class="fas fa-chevron-down fa-xs ml-1"></i>
                        </a>
                    </dd>
                </dl>

                <div class="collapse mt-2" id="permisos-collapse">
                    @php
                        $permissions = $this->getTranslatedPermissions();
                    @endphp

                    @if (empty($permissions))
                        <span class="text-muted">Sin permisos asignados</span>
                    @else
                        <ul class="mb-0 pl-4" style="column-count: 3; column-gap: 1rem;">
                            @foreach ($permissions as $p)
                                <li>{{ $p }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info">
                        <i class="fas fa-users"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Usuarios</span>
                        <span class="info-box-number">
                            {{ $role->users()->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3">
            @can('roles.edit')
                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-outline-primary mr-1">
                    <i class="fas fa-edit mr-1"></i> Editar
                </a>
            @endcan

            @can('roles.delete')
                <x-livewire.loading-button label="Eliminar" theme="outline-danger" class="mr-1" icon="trash"
                    wire:click="delete" wire:target="delete" wire:swal-confirm="¿Eliminar este rol?" swal-icon="warning" />
            @endcan

            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary mr-1">
                <i class="fas fa-fw fa-chevron-left mr-1"></i> Volver
            </a>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-6 text-muted">Creado</dt>
                    <dd class="col-6" title="{{ $role->created_at->format('d/m/Y H:i') }}" data-toggle="tooltip"
                        data-placement="left">
                        {{ $role->created_at->diffForHumans() }}
                    </dd>

                    <dt class="col-6 text-muted">Actualizado</dt>
                    <dd class="col-6 mb-0" title="{{ $role->updated_at->format('d/m/Y H:i') }}" data-toggle="tooltip"
                        data-placement="left">
                        {{ $role->updated_at->diffForHumans() }}
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
