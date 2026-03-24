<?php

namespace App\Livewire\Admin\Roles;

use App\Models\User;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class UsersTable extends Component
{
    use HasLivewireTableBehavior;

    #[Locked]
    public int $roleId;

    public string $searchTerm = '';

    public int $perPage = 12;

    public int $page = 1;

    public string $sortColumn = 'name';

    public string $sortDirection = 'desc';

    protected array $theadConfig = [
        [
            'column' => 'name',
            'label'  => 'Nombre',
        ],
        [
            'column' => 'email',
            'label'  => 'Email',
        ],
        [
            'column' => 'is_active',
            'label'  => 'Activo',
            'align'  => 'center',
            'style'  => 'width: 1%;',
        ],
        [
            'label' => 'Ver más',
            'align' => 'center',
        ],
    ];

    public function mount(int $roleId): void
    {
        $this->roleId = $roleId;
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $users = $this->getQuery()->paginate($this->perPage);

        return view('livewire.admin.roles.users-table', [
            'users' => $users,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = User::whereHas('roles', function ($query) {
            $query->where('id', $this->roleId);
        });

        if ($term = $this->searchTerm) {
            $query->whereAny(
                ['name', 'email'],
                'like',
                "%$term%"
            );
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
