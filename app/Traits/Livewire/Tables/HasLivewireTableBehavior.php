<?php

namespace App\Traits\Livewire\Tables;

use App\Traits\Livewire\WithTableSorting;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

trait HasLivewireTableBehavior
{
    use WithPagination, WithTableSorting, WithoutUrlPagination;

    public function search(): void
    {
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->searchTerm = '';
        $this->resetPage();
    }

    public function afterSortChanged(): void
    {
        $this->resetPage();
    }

    public function updatedPage($page): void
    {
        $this->page = $page;
    }
}
