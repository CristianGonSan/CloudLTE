<x-card-table :pagination="$users">
    <x-slot:header>
        <x-livewire.table.search-pane />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @forelse($users as $user)
            <tr wire:key="user-{{ $user->id }}">
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @forelse ($user->roles()->orderBy('name')->get() as $role)
                        <span class="badge badge-info">{{ $role->name }}</span>
                    @empty
                        Sin roles
                    @endforelse
                </td>
                <td class="text-center">
                    <i class="{{ $user->getActiveIconClass() }}"></i>
                </td>
                <td class="text-center">
                    <a href="{{ route('admin.users.show', $user->id) }}" class="d-block text-reset">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No se encontraron resultados.</td>
            </tr>
        @endforelse
    </tbody>
</x-card-table>
