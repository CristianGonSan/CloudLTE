<x-card-table :pagination="$documents">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($documents as $document)
            @php
                $file = $document->file();
            @endphp
            <tr wire:key="document-{{ $document->id }}">
                <td>{{ $file->file_name }}</td>
                <td class="text-center">
                    <a href="{{ route('documents.show', $document->id) }}" class="d-block text-reset">
                        <i class="fas fa-fw fa-chevron-right"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">No se encontraron resultados.</td>
            </tr>
        @endforelse
    </tbody>
</x-card-table>
