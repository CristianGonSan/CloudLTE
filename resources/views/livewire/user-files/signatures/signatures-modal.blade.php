@use('App\Enums\SignatoryStatus')
@use('App\Models\Signatory')

<div>
    <div wire:ignore.self id="modalSignatures" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="modalSignaturesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalSignaturesLabel">
                        <i class="fas fa-fw fa-file-signature mr-1"></i>
                        Firmas pendientes
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body pb-0">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="onlyPendingSwitch"
                                wire:model.live="onlyPending">
                            <label class="custom-control-label" for="onlyPendingSwitch">
                                Solo pendientes
                            </label>
                        </div>

                        <small class="text-muted" style="min-width: 90px; text-align: right;">
                            <span wire:loading.remove wire:target="onlyPending">
                                {{ $signatories->total() }}
                                {{ Str::plural('resultado', $signatories->total()) }}
                            </span>
                            <span wire:loading wire:target="onlyPending">
                                <span class="spinner-border spinner-border-sm align-middle mr-1" role="status"
                                    aria-hidden="true"></span>
                                <span class="align-middle"> Cargando…</span>
                            </span>
                        </small>
                    </div>

                    <div style="max-height: 400px; overflow-y: auto;">
                        @forelse ($signatories as $signatory)
                            @php
                                /** @var Signatory $signatory */
                                $request = $signatory->signatureRequest;
                                $userFile = $request->userFile;
                                $isPending = $signatory->status === SignatoryStatus::Pending;
                            @endphp

                            <div class="d-flex align-items-center justify-content-between p-3 mb-2 border rounded">

                                {{-- Info del archivo --}}
                                <div class="overflow-hidden mr-3">
                                    <div class="font-weight-bold text-truncate">
                                        {{ $userFile->description ?? "Archivo #$userFile->id" }}
                                    </div>
                                    <small class="text-muted">
                                        Solicitud #{{ $request->id }}
                                        &mdash;
                                        {{ $signatory->created_at->diffForHumans() }}
                                    </small>
                                </div>

                                {{-- Badge de estado + acción --}}
                                <div class="d-flex align-items-center flex-shrink-0 gap-2">
                                    <span class="badge {{ $signatory->status->badgeClass() }} mr-2">
                                        {{ $signatory->status->label() }}
                                    </span>

                                    @if ($isPending)
                                        <a href="{{ route('files.signatures.sign', [$userFile->id, $request->id, $signatory->id]) }}"
                                            class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="fa-solid fa-pen-nib mr-1"></i> Firmar
                                        </a>
                                    @else
                                        <a href="{{ route('files.signatures.show', [$userFile->id, $request->id]) }}"
                                            class="btn btn-sm btn-outline-secondary" target="_blank">
                                            <i class="fa-solid fa-eye mr-1"></i> Ver
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="fa-solid fa-circle-check fa-2x mb-2 d-block"></i>
                                <p class="mb-0">
                                    No hay firmas {{ $onlyPending ? 'pendientes' : 'registradas' }}.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    @if ($signatories->hasPages())
                        <div class="mt-3">
                            {{ $signatories->links() }}
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        Livewire.on('showSignaturesModal', () => $('#modalSignatures').modal('show'));
        Livewire.on('hideSignaturesModal', () => $('#modalSignatures').modal('hide'));
    </script>
@endpush
