<?php

namespace App\Livewire\UserFiles\Signatures;

use App\Enums\SignatoryStatus;
use App\Models\Signatory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class SignaturesModal extends Component
{
    use WithPagination;

    public bool $onlyPending = true;

    public function render(): View
    {
        return view('livewire.user-files.signatures.signatures-modal', [
            'signatories' => $this->getSignatories(),
        ]);
    }

    #[On('openSignaturesModal')]
    public function openModal(): void
    {
        $this->reset();
        $this->onlyPending = true;
        $this->dispatch('showSignaturesModal');
    }

    #[On('closeSignaturesModal')]
    public function closeModal(): void
    {
        $this->dispatch('hideSignaturesModal');
    }

    // Vuelve a la página 1 cada vez que cambia el filtro
    public function updatedOnlyPending(): void
    {
        $this->resetPage();
    }

    private function getSignatories(): LengthAwarePaginator
    {
        return Signatory::query()
            ->with(['signatureRequest.userFile'])
            ->where('user_id', Auth::id())
            ->when($this->onlyPending, fn($q) => $q->where('status', SignatoryStatus::Pending))
            ->latest()
            ->paginate(10);
    }
}
