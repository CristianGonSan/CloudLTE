@use('App\Enums\FileExtensionSupport')
@use('App\Models\UserFile')
@use('Spatie\MediaLibrary\MediaCollections\Models\Media')

<div>
    @php
        /**
         * @var Media $media
         * @var UserFile $userFile
         */
        $media = $userFile->getFile();
        $extension = FileExtensionSupport::fromExtension($media->extension);
        $fileUrl = $userFile->getUrlRandom();
        $user = $userFile->user;
    @endphp

    <form wire:submit='save' class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-fw {{ $extension->icon() }} fa-2x mr-3"
                        style="color: {{ $extension->color() }}"></i>

                    <div class="overflow-hidden w-100">
                        <a href="{{ $fileUrl }}" target="_blank" class="text-truncate">
                            {{ $media->file_name }}
                        </a>

                        <div class="d-flex justify-content-between align-items-center text-muted">
                            <small>{{ $media->extension }}</small>
                            <small>{{ $media->human_readable_size }}</small>
                        </div>

                        <small class="text-muted">
                            Subido hace {{ $userFile->created_at->diffForHumans(null, true) }}
                        </small>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <x-form.select-wire-ignore name="userId" label="Seleccionar usuarios" wire:loading.attr="readonly"
                        wire:target="save,userId" />
                    <div>
                        @forelse ($signatures as $index => $signature)
                            <div wire:key='sign-{{ $index }}' class="d-flex align-items-center mb-2">
                                <div class="flex-grow-1 mr-2">
                                    <strong class="d-block">{{ $signature['name'] }}</strong>
                                    <small class="text-muted">{{ $signature['email'] }}</small>
                                </div>

                                <x-adminlte-input type="number"
                                    name="signatures[{{ $index }}][required_signatures]" placeholder="0"
                                    step="1" min="1" max="16"
                                    wire:model='signatures.{{ $index }}.required_signatures' igroup-size="sm"
                                    fgroup-class="mb-0" required>
                                    <x-slot name="appendSlot">
                                        <div class="input-group-text">
                                            <i class="fa fa-fw fa-signature"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-input>

                                <x-livewire.loading-button icon="trash" theme="outline-danger" class="btn-sm ml-2"
                                    wire:click="remove('{{ $index }}')" />
                            </div>

                            @error("signatures.{$index}.required_signatures")
                                <small class="text-danger d-block mb-1">{{ $message }}</small>
                            @enderror
                        @empty
                            <p class="text-muted mb-0">Seleccione al menos un usuario.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <x-livewire.loading-button type='submit' label="Actualizar" />

                <a href="{{ route('files.signatures.show', [$userFile->id, $signatureRequestId]) }}"
                    class="btn btn-outline-secondary ml-1">
                    Cancelar
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <x-adminlte-textarea name="message" fgroup-class="mb-0" label="Mensaje"
                        placeholder="Mensaje opcional..." wire:model='message' maxlength="500" rows="4" />
                </div>
            </div>
        </div>
    </form>
</div>

@script
    <script>
        const builder = new LivewireSelect2Builder($wire);

        builder.appendConfig({
            allowClear: false,
            templateResult: data => {
                if (data.loading) return data.text;
                return $(`
                    <div class="p-1">
                        <strong class="d-block">${data.text}</strong>
                        <small>${data.description ?? ''}</small>
                    </div>
                `);
            },
        });

        const userSelect = builder.selector('#userId')
            .appendConfig({
                placeholder: 'Seleccionar usuarios',
                ajax: {
                    url: "{{ route('lookups.users.select2') }}",
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                },
            })
            .build();

        userSelect.on('change', function() {
            const value = $(this).val();

            if (value && value !== '') {
                $wire.addSignature(value);
                $(this).val(null).trigger('change');
            }
        });
    </script>
@endscript
