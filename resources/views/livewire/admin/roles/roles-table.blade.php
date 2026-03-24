<x-card-table :pagination="$roles">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse($roles as $role)
            <tr wire:key="role-{{ $role->id }}">
                <td>{{ $role->name }}</td>
                <td class="text-center">
                    <a href="{{ route('admin.roles.show', $role->id) }}" class="d-block text-reset">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center text-muted">No se encontraron resultados.</td>
            </tr>
        @endforelse
    </tbody>
</x-card-table>
