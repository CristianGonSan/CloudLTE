<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Session;
use Livewire\Component;

class UsersTable extends Component
{
    use HasLivewireTableBehavior;

    #[Session]
    public string $searchTerm = '';

    #[Session]
    public int $perPage = 12;

    #[Session]
    public int $page = 1;

    #[Session]
    public string $sortColumn = 'name';

    #[Session]
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
            'label' => 'Roles',
            'style' => 'min-width: 150px;',
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

    public function mount(): void
    {
        $this->setPage($this->page);
    }

    public function render(): View
    {
        $users = $this->getQuery()->paginate($this->perPage);

        return view('livewire.admin.users.users-table', [
            'users' => $users,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = User::query();

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
