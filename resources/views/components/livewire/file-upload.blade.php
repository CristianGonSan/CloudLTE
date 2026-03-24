@props(['name', 'label' => null, 'fgroupClass' => null, 'labelClass' => null, 'accept' => null, 'hint' => null])

<div class="form-group {{ $fgroupClass }}" x-data="attachmentUpload()" x-on:livewire-upload-start="onStart"
    x-on:livewire-upload-finish="onFinish" x-on:livewire-upload-error="onError"
    x-on:livewire-upload-progress="onProgress($event.detail.progress)">

    @if ($label || $hint)
        <div>
            @if ($label)
                <label for="{{ $name }}" class="{{ $labelClass }}">{{ $label }}</label>
            @endif

            @if ($hint)
                <small class="text-muted ml-1">{{ $hint }}</small>
            @endif
        </div>
    @endif

    <div class="custom-file" x-show="!uploading">
        <input type="file" id="{{ $name }}"
            class="custom-file-input cursor-pointer @error($name) is-invalid @enderror" wire:model="{{ $name }}"
            @if ($accept) accept="{{ $accept }}" @endif>
        <label class="custom-file-label" for="{{ $name }}">
            {{ $slot->isNotEmpty() ? $slot : 'Seleccionar archivo' }}
        </label>
    </div>

    <div x-show="uploading" x-cloak>
        <div class="d-flex justify-content-between mb-1">
            <small class="text-muted">
                <i class="fas fa-spinner fa-spin mr-1"></i>Subiendo archivo...
            </small>
            <small class="text-muted font-weight-bold" x-text="progress + '%'"></small>
        </div>
        <div class="progress" style="height: 15px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                :style="'width: ' + progress + '%'" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100">
            </div>
        </div>
    </div>

    @error($name)
        <span class="invalid-feedback d-block">{{ $message }}</span>
    @enderror
</div>

@once
    @push('js')
        <script>
            function attachmentUpload() {
                return {
                    uploading: false,
                    progress: 0,
                    onStart() {
                        this.uploading = true;
                        this.progress = 0;
                    },
                    onFinish() {
                        this.uploading = false;
                        this.progress = 0;
                    },
                    onError() {
                        this.uploading = false;
                        this.progress = 0;
                    },
                    onProgress(value) {
                        this.progress = value;
                    },
                };
            }
        </script>
    @endpush
@endonce
