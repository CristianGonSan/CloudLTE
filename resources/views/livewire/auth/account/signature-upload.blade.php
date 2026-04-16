@use('App\Enums\FileExtensionSupport')
@use('Illuminate\Support\Number')
@use('Livewire\Features\SupportFileUploads\TemporaryUploadedFile')

<div>
    <div class="card">
        <div class="card-body">
            @php
                $user = auth()->user();
                $signature = $user->getSignature();
            @endphp

            @if ($signature)
                <div class="d-flex flex-column align-items-center w-100 border rounded p-3">
                    <img src="{{ $user->getSignatureUrlRamdon() }}" alt="Firma de {{ $user->name }}" class="img-fluid"
                        style="max-height: 120px; object-fit: contain;">
                    <div class="d-flex flex-column align-items-center mt-2" style="max-width: 100%;">
                        <small class="text-muted text-truncate w-100 text-center">
                            {{ $signature->file_name }}
                        </small>
                        <small class="text-muted">
                            {{ Number::fileSize($signature->size, 2) }}
                            &mdash;
                            {{ $signature->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            @else
                <div class="text-muted">
                    <i class="fas fa-file-signature mr-1"></i>
                    No tiene firma registrada. Suba una para continuar.
                </div>
            @endif
        </div>
    </div>

    <div class="mb-3 mt-3">
        <button class="btn btn-outline-primary" wire:click="openModal">
            <i class="fas fa-fw fa-file-arrow-up mr-1"></i>
            {{ $signature ? 'Reemplazar firma' : 'Subir firma' }}
        </button>
    </div>

    {{-- Modal --}}
    <div wire:ignore.self id="modalSignatureUpload" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="modalSignatureUploadLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalSignatureUploadLabel">Subir firma</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"
                        wire:click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <x-livewire.file-upload name="file"
                        accept="image/jpg,image/jpeg,image/png,image/svg+xml,image/webp">
                        {{ $file?->getClientOriginalName() ?? 'Seleccionar imagen' }}
                    </x-livewire.file-upload>

                    @if ($file)
                        @php
                            /** @var TemporaryUploadedFile $file */
                            $extension = FileExtensionSupport::fromExtension($file->getClientOriginalExtension());
                            $friendlySize = Number::fileSize($file->getSize(), 2);
                        @endphp

                        {{-- Previsualización de la imagen --}}
                        <div class="d-flex justify-content-center align-items-center w-100 border rounded p-3 mb-3">
                            <img src="{{ $file->temporaryUrl() }}" alt="Vista previa" class="img-fluid"
                                style="max-height: 150px; object-fit: contain;">
                        </div>

                        <div
                            class="d-flex align-items-center mb-3 p-3 border rounded @error('file') border-danger @enderror">
                            <i class="fa-solid fa-fw {{ $extension->icon() }} fa-2x mr-3"
                                style="color: {{ $extension->color() }}"></i>

                            <div class="overflow-hidden w-100">
                                <div class="text-truncate">
                                    {{ $file->getClientOriginalName() }}
                                </div>

                                <div class="d-flex justify-content-between align-items-center text-muted">
                                    <small>{{ strtoupper($file->getClientOriginalExtension()) }}</small>
                                    <small>{{ $friendlySize }}</small>
                                </div>
                            </div>
                        </div>

                        @error('file')
                            <div class="text-danger small mt-1">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </div>
                        @enderror
                    @endif
                </div>

                <div class="modal-footer">
                    <x-livewire.loading-button theme="outline-secondary" label="Cancelar" icon="cancel"
                        wire:click="closeModal" />

                    <x-livewire.loading-button theme="outline-primary" label="Guardar" wire:click="save"
                        wire:target="save" />
                </div>

            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        Livewire.on('showModalSignatureUpload', function() {
            $('#modalSignatureUpload').modal('show');
        });

        Livewire.on('hideModalSignatureUpload', function() {
            $('#modalSignatureUpload').modal('hide');
        });
    </script>
@endpush
