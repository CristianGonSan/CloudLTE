<x-card-table :pagination="$categories">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse ($categories as $category)
            <tr wire:key="category-{{ $category->id }}">
                <td>{{ $category->mediumText('name') }}</td>
                <td class="text-center"><i class="{{ $category->getActiveIconClass() }}"></i></td>
                <td class="text-center">
                    <a href="{{ route('document-categories.show', $category->id) }}" class="d-block text-reset">
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
