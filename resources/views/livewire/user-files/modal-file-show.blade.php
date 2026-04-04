@use('App\Enums\FileExtensionSupport')
@use('App\Models\UserFile')
@use('Spatie\MediaLibrary\MediaCollections\Models\Media')

<div>
    <div wire:ignore.self id="modalUserFileShow" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="modalUserFileShowLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content border-0 shadow-lg">
                @if ($userFile)
                    @php
                        /**
                         * @var Media $media
                         * @var UserFile $userFile
                         */
                        $media = $userFile->getFile();
                        $extension = FileExtensionSupport::fromExtension($media->extension);
                        $viewer = $extension->viewer();
                        $fileUrl = $userFile->getUrl();
                        $user = $userFile->user;
                    @endphp

                    <div class="modal-header align-items-center">
                        <div class="d-flex align-items-center overflow-hidden flex-grow-1 mr-2">
                            <i class="fas fa-fw {{ $extension->icon() }} fa-lg mr-2 flex-shrink-0"
                                style="color: {{ $extension->color() }}"></i>
                            <div class="modal-title m-0 text-truncate" id="modalUserFileShowLabel"
                                title="{{ $media->file_name }}">
                                {{ $media->file_name }}
                            </div>
                        </div>
                        <button type="button" class="close ml-2 flex-shrink-0" data-dismiss="modal"
                            aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-0">
                        <div class="row no-gutters">
                            <div class="col-md-8 bg-dark d-flex align-items-center justify-content-center"
                                style="min-height: 512px;">
                                @if ($extension->isViewerSupported())
                                    @if ($viewer === 'image')
                                        <img src="{{ $fileUrl }}" alt="{{ $media->file_name }}"
                                            style="width: 100%; height: 562px; object-fit: contain;">
                                    @elseif ($viewer === 'video')
                                        <video controls class="w-100" style="max-height: 562px;">
                                            <source src="{{ $fileUrl }}" type="{{ $media->mime_type }}">
                                        </video>
                                    @elseif ($viewer === 'audio')
                                        <audio controls class="w-100 px-4">
                                            <source src="{{ $fileUrl }}" type="{{ $media->mime_type }}">
                                        </audio>
                                    @else
                                        {{-- pdf, text --}}
                                        <iframe id="viewer-frame" src="{{ $fileUrl }}" class="w-100 border-0"
                                            style="height: 562px;" allowfullscreen>
                                        </iframe>
                                    @endif
                                @else
                                    <div class="text-center text-white-50 p-4">
                                        <i class="fas {{ $extension->icon() }} fa-5x mb-3"
                                            style="color: {{ $extension->color() }}"></i>
                                        <p class="h6">Previsualización no disponible</p>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4 d-flex flex-column border-left">
                                <div class="flex-grow-1 p-3">
                                    <div class="d-flex align-items-start mb-4">
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center mr-3"
                                            style="width: 36px; height: 36px;">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="font-weight-bold text-dark text-truncate"
                                                style="line-height: 1.2;">
                                                {{ $user->name }}
                                            </div>
                                            <small class="text-muted">
                                                Subido hace {{ $media->created_at->diffForHumans(null, true) }}
                                            </small>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-start mb-4">
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

                                </div>

                                <div class="p-3 border-top">
                                    <div class="d-flex justify-content-between">

                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            data-dismiss="modal">
                                            Cerrar
                                        </button>

                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ $userFile->getUrl(true) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-download mr-1"></i> Descargar
                                            </a>
                                            <button type="button"
                                                class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-fw fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="{{ $userFile->getUrl() }}"
                                                    target="_blank">
                                                    <i class="fas fa-external-link-alt mr-2"></i>
                                                    Abrir en otra ventana
                                                </a>
                                                @can('delete', $userFile)
                                                    <div class="dropdown-divider"></div>
                                                    <button class="dropdown-item text-danger"
                                                        wire:click="delete({{ $userFile->id }})"
                                                        wire:swal-confirm="¿Eliminar archivo?">
                                                        <i class="fas fa-trash-alt mr-2"></i>
                                                        Eliminar
                                                    </button>
                                                @endcan
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="modal-header"></div>
                    <div class="modal-body">
                        <div style="min-height: 556px"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        Livewire.on('showModalUserFileShow', function() {
            $('#modalUserFileShow').modal('show');
        });

        Livewire.on('hideModalUserFileShow', function() {
            $('#modalUserFileShow').modal('hide');
        });
    </script>
@endpush
