@use('App\Enums\FileExtensionSupport')
@use('App\Models\SignatureRequest')
@use('App\Models\UserFile')
@use('Spatie\MediaLibrary\MediaCollections\Models\Media')

<div>
    @php
        /**
         * @var SignatureRequest $signatureRequest
         * @var UserFile $userFile
         */
        $userFile = $signatureRequest->userFile;
        $media = $userFile->getFile();
        $extension = FileExtensionSupport::fromExtension($media->extension);
        $fileUrl = $userFile->getUrlRandom();
        $userId = auth()->id();
        $mySignatury = $signatureRequest->getSignatoryByUserId($userId);
        $mySignaturyId = $mySignatury?->id;
    @endphp

    <div class="row">
        <div class="col-md-8">

            {{-- Archivo --}}
            <div class="card mb-3">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-fw {{ $extension->icon() }} fa-2x mr-3"
                        style="color: {{ $extension->color() }}"></i>

                    <div class="overflow-hidden w-100">
                        <a href="{{ $fileUrl }}" target="_blank" class="text-truncate d-block font-weight-bold">
                            {{ $media->file_name }}
                        </a>

                        <div class="d-flex justify-content-between align-items-center text-muted">
                            <small>{{ strtoupper($media->extension) }}</small>
                            <small>{{ $media->human_readable_size }}</small>
                        </div>

                        <small class="text-muted">
                            Subido hace {{ $userFile->created_at->diffForHumans(null, true) }}
                        </small>
                    </div>
                </div>
            </div>

            {{-- Firmantes --}}
            <h2 class="h5">Firmantes ({{ $signatureRequest->signatories()->count() }})</h2>
            <div class="card">
                <div class="card-body p-0">
                    @forelse ($signatureRequest->signatories as $signatory)
                        <div class="d-flex align-items-center px-3 py-2 border-bottom">

                            {{-- Nombre y correo --}}
                            <div class="flex-grow-1">
                                <strong class="d-block">{{ $signatory->user->name }}
                                    @if ($signatory->user_id === $userId)
                                        <span class="text-success">(Tú)</span>
                                    @endif
                                </strong>
                                <small class="text-muted">{{ $signatory->user->email }}</small>
                            </div>

                            {{-- Firmas requeridas --}}
                            <div class="text-center mx-3">
                                <small class="d-block">
                                    {{ $signatory->required_signatures }}
                                    {{ $signatory->required_signatures === 1 ? 'firma requerida' : 'firmas requeridas' }}
                                </small>
                            </div>

                            {{-- Estado del firmante --}}
                            <div class="text-center mx-2">
                                <span class="badge {{ $signatory->status->badgeClass() }}">
                                    {{ $signatory->status->label() }}
                                </span>
                                @if ($signatory->status_changed_at)
                                    <small class="text-muted d-block">
                                        {{ $signatory->status_changed_at->diffForHumans() }}
                                    </small>
                                @endif
                            </div>

                        </div>
                    @empty
                        <p class="text-muted mb-0 p-3">
                            <i class="fas fa-users mr-1"></i> Sin firmantes registrados.
                        </p>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Panel lateral --}}
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">

                    {{-- Estado de la solicitud --}}
                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-center mr-3"
                            style="width: 36px; height: 36px; flex-shrink: 0;">
                            <i class="fas fa-flag text-muted"></i>
                        </div>
                        <div>
                            <div class="font-weight-bold text-dark" style="line-height: 1.2;">
                                Estado
                            </div>
                            <span class="badge {{ $signatureRequest->status->badgeClass() }}">
                                {{ $signatureRequest->status->label() }}
                            </span>
                        </div>
                    </div>

                    {{-- Fechas --}}
                    @if ($signatureRequest->status_change_at)
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mr-3"
                                style="width: 36px; height: 36px; flex-shrink: 0;">
                                <i class="fas fa-clock text-muted"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold text-dark" style="line-height: 1.2;">
                                    Cambio de estado
                                </div>
                                <small class="text-muted">
                                    {{ $signatureRequest->status_change_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-center mr-3"
                            style="width: 36px; height: 36px; flex-shrink: 0;">
                            <i class="fas fa-clock text-muted"></i>
                        </div>
                        <div>
                            <div class="font-weight-bold text-dark" style="line-height: 1.2;">
                                Creada {{ $signatureRequest->created_at->diffForHumans() }}
                            </div>
                            <small class="text-muted">
                                Actualizada {{ $signatureRequest->updated_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>

                    {{-- Mensaje opcional --}}
                    @if ($signatureRequest->message)
                        <div class="d-flex align-items-start">
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mr-3"
                                style="width: 36px; height: 36px; flex-shrink: 0;">
                                <i class="fas fa-message text-muted"></i>
                            </div>
                            <div>
                                <div class="font-weight-bold text-dark" style="line-height: 1.2;">
                                    Mensaje
                                </div>
                                <small class="text-muted">{{ $signatureRequest->message }}</small>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Acciones --}}
            <div class="mb-3">
                @can('edit', $signatureRequest)
                    <a href="{{ route('files.signatures.edit', [$userFile->id, $signatureRequest->id]) }}"
                        class="btn btn-outline-primary mr-1">
                        <i class="fas fa-edit mr-1"></i> Editar
                    </a>
                @endcan

                @can('sign', $signatureRequest)
                    <a href="{{ route('files.signatures.sign', [$userFile->id, $signatureRequest->id, $mySignaturyId]) }}"
                        class="btn btn-outline-primary mr-1">
                        <i class="fas fa-signature mr-1"></i> Firmar
                    </a>
                @endcan

                @can('cancel', $signatureRequest)
                    <x-livewire.loading-button theme="outline-danger" class="mr-1" icon="ban" label="Cancelar"
                        wire:click='cancel' wire:swal-confirm="¿Desaea cancelar estas solicitud de firmas?" />
                @endcan
            </div>
        </div>
    </div>
</div>
