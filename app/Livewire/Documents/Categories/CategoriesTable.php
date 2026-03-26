<?php

namespace App\Livewire\Documents\Categories;

use App\Models\DocumentCategory;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Session;
use Livewire\Component;

class CategoriesTable extends Component
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
        $categories = $this->getQuery()->paginate($this->perPage);

        return view('livewire.documents.categories.categories-table', [
            'categories' => $categories,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = DocumentCategory::query();

        if ($term = $this->searchTerm) {
            $query->where(function ($q) use ($term) {
                $q->whereAny(
                    ['name'],
                    'like',
                    "%$term%"
                );
            });
        }

        $query->orderBy($this->sortColumn, $this->sortDirection);

        return $query;
    }
}
