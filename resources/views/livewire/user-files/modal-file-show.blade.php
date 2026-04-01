@use('App\Enums\FileExtensionSupport')
@use('App\Models\UserFile')
@use('Spatie\MediaLibrary\MediaCollections\Models\Media')

<div>
    <div wire:ignore.self id="modalUserFileShow" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="modalUserFileShowLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalUserFileShowLabel">Archivo #{{ $userFile?->id ?? 0 }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    @if ($userFile)
                        @php
                            /**
                             * @var Media $media;
                             * @var UserFile $userFile;
                             */
                            $media = $userFile->getFile();
                            $extension = FileExtensionSupport::fromExtension($media->extension);
                        @endphp
                    @endif
                </div>

                <div class="modal-footer">

                </div>
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
