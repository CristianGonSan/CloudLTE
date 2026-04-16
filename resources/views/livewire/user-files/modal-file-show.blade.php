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

                        $signatureRequest = $userFile->getLastSignatureRequest();
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
                            <div class="col-md-8">
                                @include('partials.files.viewer')
                            </div>

                            <div class="col-md-4 d-flex flex-column border-left">
                                <div class="flex-grow-1 p-3">
                                    <div class="d-flex align-items-start mb-3">
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
                                        <div class="mb-3">
                                            <small
                                                class="text-muted text-uppercase font-weight-bold">Descripción</small>
                                            <p class="small mb-0 mt-1">{{ $userFile->description }}</p>
                                        </div>
                                    @endif

                                    @if ($signatureRequest)
                                        <a href="{{ route('files.signatures.show', [$userFile->id, $signatureRequest->id]) }}"
                                            target="_blank"
                                            class="d-flex align-items-center border rounded p-2 text-decoration-none text-dark">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <small class="text-muted d-block">Última solicitud de firma</small>
                                                <span
                                                    class="font-weight-bold">{{ $signatureRequest->status->label() }}</span>
                                            </div>
                                            <small class="text-muted ml-2 text-nowrap">
                                                {{ $signatureRequest->created_at->diffForHumans() }}
                                            </small>
                                        </a>
                                    @endif
                                </div>

                                <div class="p-3 border-top">
                                    <div class="d-flex justify-content-between">

                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            data-dismiss="modal">
                                            Cerrar
                                        </button>

                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ $userFile->getUrl(true) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-fw fa-download mr-1"></i> Descargar
                                            </a>
                                            <button type="button"
                                                class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-fw fa-fw fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="{{ $userFile->getUrl() }}"
                                                    target="_blank">
                                                    <i class="fas fa-fw fa-external-link-alt mr-2"></i>
                                                    Abrir en otra ventana
                                                </a>
                                                @can('editFile', $userFile)
                                                    @if ($extension->editor() === 'pdf')
                                                        <a class="dropdown-item"
                                                            href="{{ route('files.editor.pdf', $userFile->id) }}">
                                                            <i class="fas fa-fw fa-edit mr-2"></i>
                                                            Editar PDF
                                                        </a>
                                                    @endif
                                                @endcan
                                                @can('createSignatureRequest', $userFile)
                                                    <a class="dropdown-item"
                                                        href="{{ route('files.signatures.create', $userFile->id) }}">
                                                        <i class="fas fa-fw fa-file-contract mr-2"></i>
                                                        Solicitar firmas
                                                    </a>
                                                @endcan
                                                @can('delete', $userFile)
                                                    <div class="dropdown-divider"></div>
                                                    <button class="dropdown-item text-danger"
                                                        wire:click="delete({{ $userFile->id }})"
                                                        wire:swal-confirm="¿Eliminar archivo?">
                                                        <i class="fas fa-fw fa-trash-alt mr-2"></i>
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

        $('#modalUserFileShow').on('hidden.bs.modal', function() {
            const url = new URL(window.location.href);

            url.searchParams.delete('show_file');

            window.history.replaceState({}, document.title, url.toString());
        });
    </script>
@endpush
