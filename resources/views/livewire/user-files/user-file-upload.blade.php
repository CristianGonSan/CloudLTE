@use('App\Enums\FileExtensionSupport')
@use('Illuminate\Support\Number')
@use('Livewire\Features\SupportFileUploads\TemporaryUploadedFile')

<div>
    <div wire:ignore.self id="modalUserFileUpload" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="modalUserFileUploadLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalUserFileUploadLabel">Subir archivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    @if ($file)
                        @php
                            /**
                             * @var TemporaryUploadedFile $file;
                             */
                            $extension = FileExtensionSupport::fromExtension($file->getClientOriginalExtension());
                            $friendlySize = Number::fileSize($file->getSize(), 2);
                        @endphp

                        <div
                            class="d-flex align-items-center mb-3 p-3 border rounded @error('file') border-danger @enderror">
                            <i class="fa-solid fa-fw {{ $extension->icon() }} fa-2x mr-3"
                                style="color: {{ $extension->color() }}"></i>

                            <div class="overflow-hidden w-100">
                                <div class="text-truncate">
                                    {{ $file->getClientOriginalName() }}
                                </div>

                                <div class="d-flex justify-content-between align-items-center text-muted">
                                    <small>
                                        {{ $file->getClientOriginalExtension() }}
                                    </small>

                                    <small>
                                        {{ $friendlySize }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif

                    <x-livewire.file-upload name="file">
                        {{ $file?->getClientOriginalName() ?? 'Seleccionar archivo' }}
                    </x-livewire.file-upload>

                    <x-adminlte-textarea name="notes" fgroup-class="mb-0" placeholder="notas..." wire:model='notes'
                        maxlength="500" />
                </div>

                <div class="modal-footer">
                    <x-livewire.loading-button theme="outline-secondary" label="Cancelar" icon="cancel"
                        wire:click="closeModal" />

                    <x-livewire.loading-button theme="outline-primary" label="Guardar" wire:click="save"
                        wire:target='save' />
                </div>

            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        Livewire.on('showModalUserFileUpload', function() {
            $('#modalUserFileUpload').modal('show');
        });

        Livewire.on('hideModalUserFileUpload', function() {
            $('#modalUserFileUpload').modal('hide');
        });
    </script>
@endpush
