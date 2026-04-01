<?php

namespace App\Livewire\UserFiles;

use App\Models\UserFile;
use App\Traits\SweetAlert2\Livewire\Toast;
use Livewire\Attributes\On;
use Livewire\Component;

class ModalFileShow extends Component
{
    use Toast;

    public ?int $userFileId = null;

    public function render()
    {
        return view('livewire.user-files.modal-file-show', [
            'userFile' => $this->userFile()
        ]);
    }

    #[On('openComponentUserFileShow')]
    public function openModal(int $userFileId): void
    {
        $this->userFileId = $userFileId;
        $this->dispatch('showModalUserFileShow');
    }

    #[On('closeComponentUserFileShow')]
    public function closeModal(): void
    {
        $this->dispatch('hideModalUserFileShow');
    }

    private function userFile(): ?UserFile
    {
        if ($this->userFileId) {
            return UserFile::with(['media'])->findOr($this->userFileId);
        }
        return null;
    }
}
