<?php

namespace App\Livewire\UserFiles;

use App\Enums\FileExtensionGroups;
use App\Models\UserFile;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UserFilesList extends Component
{
    use WithPagination, Toast;

    #[Url(as: 'search_term')]
    public $searchTerm = '';

    #[Url(as: 'only_my_files')]
    public $onlyMyFiles = false;

    #[Url]
    public $group = 'all';

    #[Url(as: 'date_from')]
    public $dateFrom = '';

    #[Url(as: 'date_to')]
    public $dateTo = '';

    #[Url(as: 'sort')]
    public $orderBy = 'latest';

    #[Url(as: 'per_page')]
    public $perPage = 24;

    public function render(): View
    {
        return view('livewire.user-files.user-files-list', [
            'userFiles'     => $this->getQuery()->paginate($this->perPage),
            'groupOptions'  => FileExtensionGroups::toSelectOptions()
        ]);
    }

    public function delete(int $fileId)
    {
        $userFile = UserFile::findOrFail($fileId);

        if ($this->authorize('delete', $userFile)) {
            $userFile->hardDelete();
            $this->toastSuccess("Archivo eliminado");
        } else {
            $this->toastError("Sin autorización");
        }
    }

    public function updated(): void
    {
        $this->resetPage();
    }

    private function getQuery(): Builder
    {
        $query = UserFile::with(['media', 'user'])
            ->leftJoin('media', function ($join) {
                $join->on('user_files.id', '=', 'media.model_id')
                    ->where('media.model_type', '=', UserFile::class)
                    ->where('media.collection_name', '=', 'file');
            })
            ->leftJoin('users', 'user_files.user_id', '=', 'users.id')
            ->select('user_files.*');

        if ($this->onlyMyFiles) {
            $query->where('user_files.user_id', auth()->id());
        }

        if ($mimeArray = $this->resolveMime()) {
            $query->whereIn('media.mime_type', $mimeArray);
        }

        if ($term = trim($this->searchTerm)) {
            $query->where(function (Builder $q) use ($term) {
                $q->where('media.name', 'like', "%{$term}%");
                if (!$this->onlyMyFiles) {
                    $q->orWhere('users.name', 'like', "%{$term}%");
                }
            });
        }

        if ($this->dateFrom) {
            $query->whereDate('user_files.created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('user_files.created_at', '<=', $this->dateTo);
        }

        $this->applySorting($query);

        return $query;
    }

    private function applySorting(Builder $query): void
    {
        switch ($this->orderBy) {
            case 'latest':
                $query->orderByDesc('user_files.created_at');
                break;
            case 'older':
                $query->orderBy('user_files.created_at');
                break;
            case 'nameAsc':
                $query->orderBy('media.name');
                break;
            case 'largerSize':
                $query->orderByDesc('media.size');
                break;
            default:
                $query->orderByDesc('user_files.created_at');
        }
    }

    private function resolveMime(): ?array
    {
        return $this->group !== 'all'
            ? FileExtensionGroups::from($this->group)->mimes()
            : null;
    }

    #[On('fileSaved')]
    public function resetFilters(): void
    {
        $this->reset(['searchTerm', 'group', 'onlyMyFiles', 'dateFrom', 'dateTo', 'orderBy']);
        $this->resetPage();
    }
}
