@props([
    'viewer' => null,
    'height' => '512px',
])

<div class="bg-dark d-flex align-items-center justify-content-center" style="min-height: {{ $height }};">
    @if ($viewer)
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
            <iframe id="viewer-frame" src="{{ $fileUrl }}" class="w-100 border-0" style="height: 562px;"
                allowfullscreen>
            </iframe>
        @endif
    @else
        <div class="text-center text-white-50 p-4">
            <i class="fas {{ $extension->icon() }} fa-5x mb-3" style="color: {{ $extension->color() }}"></i>
            <p class="h6">Previsualización no disponible</p>
        </div>
    @endif
</div>
