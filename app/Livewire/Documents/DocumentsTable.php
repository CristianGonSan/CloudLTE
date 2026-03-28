<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Traits\Livewire\Tables\HasLivewireTableBehavior;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Session;
use Livewire\Component;

class DocumentsTable extends Component
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
        $documents = $this->getQuery()->paginate($this->perPage);

        return view('livewire.documents.documents-table', [
            'documents' => $documents,
        ]);
    }

    private function getQuery(): Builder
    {
        $query = Document::with('media');

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
