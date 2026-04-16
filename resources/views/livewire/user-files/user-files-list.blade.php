@use('App\Enums\FileExtensionSupport')
@use('App\Models\UserFile')
@use('Spatie\MediaLibrary\MediaCollections\Models\Media')

<div>
    <div class="row align-items-center mb-2">
        <div class="col-md-4 mb-1">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text border-right-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                </div>
                <input wire:model.live.debounce.300ms="searchTerm" type="text" class="form-control border-left-0"
                    placeholder="Buscar por nombre...">
            </div>
        </div>

        <div class="col-md-4 mb-1">
            <select wire:model.live="group" class="custom-select">
                <option value="all">Todos los tipos</option>
                @foreach ($groupOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 mb-1">
            <select wire:model.live="orderBy" class="custom-select">
                <option value="latest">Más reciente</option>
                <option value="older">Más antiguo</option>
                <option value="nameAsc">Nombre (A-Z)</option>
                <option value="largerSize">Mayor tamaño</option>
            </select>
        </div>
    </div>

    <div class="row align-items-center mb-2">
        <div class="col-md-4 mb-1">
            <input type="date" wire:model.live="dateFrom" class="form-control" title="Desde">
        </div>

        <div class="col-md-4 mb-1">
            <input type="date" wire:model.live="dateTo" class="form-control" title="Hasta">
        </div>

        <div class="col-md-4 mb-1">
            <div class="form-control d-flex align-items-center px-3">
                <x-checkbox id="onlyMyDocs" name="onlyMyFiles" label="Solo los míos" wire:model.live="onlyMyFiles" />
            </div>
        </div>
    </div>

    <div class="row">
        @forelse ($userFiles as $userFile)
            @php
                /**
                 * @var Media $media
                 * @var UserFile $userFile
                 */
                $media = $userFile->getFile();
                $extension = FileExtensionSupport::fromExtension($media->extension);
            @endphp
            <div class="col-xl-3 col-lg-4 col-md-6" wire:key='userFile-{{ $userFile->id }}'>
                <div class="card card-hover-shadow cursor-pointer">

                    <div class="card-header py-2 d-flex align-items-center bg-transparent border-bottom-0">
                        <div class="d-flex align-items-center overflow-hidden">
                            <i class="fa-solid {{ $extension->icon() }} fa-lg mr-2"
                                style="color: {{ $extension->color() }}"></i>
                            <small class="text-muted">
                                {{ $media->extension }}
                            </small>
                        </div>

                        <div class="ml-auto card-tools">
                            <div class="dropdown">
                                <button class="btn btn-tool btn-sm" type="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route('files.show', $userFile->id) }}">
                                        <i class="fas fa-fw fa-eye fa-fw mr-2"></i>Ver detalles
                                    </a>
                                    <a class="dropdown-item" href="{{ $userFile->getUrl() }}" target="_blank">
                                        <i class="fas fa-fw fa-external-link-alt fa-fw mr-2"></i>Ver archivo
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ $userFile->getUrl(true) }}">
                                        <i class="fas fa-fw fa-download fa-fw mr-2"></i>Descargar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body py-2">
                        <div class="mb-1 font-weight-bold text-truncate h6" title="{{ $media->name }}">
                            {{ $media->name }}
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="small text-muted">
                                {{ $media->human_readable_size }}
                            </span>
                        </div>
                    </div>

                    <div class="card-footer py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-truncate mr-2" title="{{ $userFile->user->name }}">
                                <small class="text-muted">
                                    {{ $userFile->user->shortText('name') }}
                                </small>
                            </div>
                            <div class="text-nowrap">
                                <small class="text-muted">
                                    {{ $userFile->created_at->diffForHumans(null, true) }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
                <p class="text-muted">No se encontraron archivos con los criterios seleccionados.</p>
            </div>
        @endforelse
    </div>

    {{ $userFiles->links() }}
</div>
