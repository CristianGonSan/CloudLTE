<x-card-table :pagination="$users">
    <x-slot:header>
        <x-livewire.table.search-pane :autofocus="false" />
    </x-slot:header>

    {{ $this->thead() }}

    <tbody>
        @php
            $canView = can('users.view');
        @endphp
        @forelse($users as $user)
            <tr wire:key="user-{{ $user->id }}">
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td class="text-center">
                    <i class="{{ $user->getActiveIconClass() }}"></i>
                </td>
                <td class="text-center">
                    @if ($canView)
                        <a href="{{ route('admin.users.show', $user->id) }}" class="d-block text-reset" target="_blank">
                            <i class="fas fa-fw fa-arrow-up-right-from-square"></i>
                        </a>
                    @else
                        <i class="fas fa-fw fa-lock text-muted"></i>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No se encontraron resultados.</td>
            </tr>
        @endforelse
    </tbody>
</x-card-table>
