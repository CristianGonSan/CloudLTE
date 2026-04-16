@use('App\Enums\FileExtensionSupport')
@use('App\Models\UserFile')
@use('Spatie\MediaLibrary\MediaCollections\Models\Media')

<div>
    @php
        /**
         * @var UserFile $userFile
         * @var Media $media
         */
        $media = $userFile->getFile();
        $extension = $userFile->getFileExtensionSupport();
        $fileUrl = $userFile->getUrlRandom();
        $user = $userFile->user;
        $signatureRequest = $userFile->getLastSignatureRequest();
    @endphp

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="card-title text-truncate" title="{{ $media->file_name }}">
                        {{ $media->file_name }}
                    </h1>
                    <div class="card-tools">
                        <div class="dropdown">
                            <button class="btn btn-tool btn-sm" type="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ $userFile->getUrlRandom() }}" target="_blank">
                                    <i class="fas fa-fw fa-external-link-alt fa-fw mr-2"></i>Ver archivo
                                </a>
                                @can('delete', $userFile)
                                    <button class="dropdown-item text-danger" wire:click='delete({{ $userFile->id }})'
                                        wire:swal-confirm="¿Eliminar archivo?">
                                        <i class="fas fa-fw fa-trash-alt fa-fw mr-2"></i>Eliminar
                                    </button>
                                @endcan

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ $userFile->getUrlRandom(true) }}">
                                    <i class="fas fa-fw fa-download fa-fw mr-2"></i>Descargar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    @include('partials.files.viewer', [
                        'viewer' => $extension->viewer(),
                    ])
                </div>

                <div class="card-footer py-2"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mr-3"
                                style="width: 36px; height: 36px;">
                                <i class="fas fa-user text-muted"></i>
                            </div>
                            <div class="overflow-hidden">
                                <div class="font-weight-bold text-dark text-truncate" style="line-height: 1.2;">
                                    {{ $user->name }}
                                </div>
                                <small class="text-muted">
                                    Subido hace {{ $userFile->created_at->diffForHumans(null, true) }}
                                </small>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mr-3"
                                style="width: 36px; height: 36px;">
                                <i class="fas fa-info-circle text-muted"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold text-dark" style="line-height: 1.2;">
                                    .{{ strtoupper($media->extension) }}
                                </div>
                                <small class="text-muted">
                                    Tamaño: {{ $media->human_readable_size }}
                                </small>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mr-3"
                                style="width: 36px; height: 36px;">
                                <i class="fas fa-code-branch text-muted"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold text-dark" style="line-height: 1.2;">
                                    Versión {{ $userFile->version ?? '1.0' }}
                                </div>
                                <small class="text-muted">
                                    @if ($userFile->edited_at)
                                        Editado hace {{ $userFile->edited_at->diffForHumans(null, true) }}
                                    @else
                                        Sin ediciones
                                    @endif
                                </small>
                            </div>
                        </div>

                        @if ($userFile->description)
                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded d-flex align-items-center justify-content-center mr-3"
                                    style="width: 36px; height: 36px; flex-shrink: 0;">
                                    <i class="fas fa-comment text-muted"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold text-dark" style="line-height: 1.2;">
                                        Descripción
                                    </div>
                                    <small class="text-muted">{{ $userFile->description }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($signatureRequest)
                <h2 class="h5">Solicitud de firmas</h2>
                <div class="card">
                    <div class="card-body p-0">
                        <a href="{{ route('files.signatures.show', [$userFile->id, $signatureRequest->id]) }}"
                            class="d-flex align-items-center border rounded p-3 text-decoration-none text-dark">
                            <div class="flex-grow-1 overflow-hidden">
                                <small class="text-muted d-block">Última solicitud</small>
                                <span class="font-weight-bold">{{ $signatureRequest->status->label() }}</span>
                            </div>
                            <small class="text-muted ml-2 text-nowrap">
                                {{ $signatureRequest->created_at->diffForHumans() }}
                            </small>
                        </a>
                    </div>
                </div>
            @endif

            @can('editFile', $userFile)
                @if ($extension->editor() === 'pdf')
                    <a href="{{ route('files.editor.pdf', $userFile->id) }}" class="btn btn-outline-primary mr-1">
                        <i class="fas fa-fw fa-edit mr-1"></i> Editar PDF
                    </a>
                @endif
            @endcan
            @can('createSignatureRequest', $userFile)
                <a href="{{ route('files.signatures.create', $userFile->id) }}" class="btn btn-outline-primary mr-1">
                    <i class="fas fa-fw fa-file-contract mr-1"></i> Solicitar firmas
                </a>
            @endcan
        </div>
    </div>
</div>
