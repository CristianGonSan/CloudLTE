<?php

namespace App\Livewire\Admin\Roles;

use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Session;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RolesTable extends Component
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
        $roles = $this->getQuery()->paginate($this->perPage);

        return view('livewire.admin.roles.roles-table', [
            'roles' => $roles,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = Role::query();

        if ($term = $this->searchTerm) {
            $query->where('name', 'like', "%$term%");
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
