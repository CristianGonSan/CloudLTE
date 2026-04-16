<?php

namespace App\Livewire\UserFiles;

use App\Models\UserFile;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Livewire\Attributes\Locked;
use Livewire\Component;

class UserFileShow extends Component
{
    use Toast, FlashToast;

    #[Locked]
    public int $userFileId;

    private ?UserFile $cachedUserFile = null;

    public function mount(int $userFileId): void
    {
        $this->userFileId = $userFileId;
    }

    public function render()
    {
        return view('livewire.user-files.user-file-show', [
            'userFile' => $this->userFile(),
        ]);
    }

    public function delete(): void
    {
        $userFile = $this->userFile();

        if ($this->authorize('delete', $userFile)) {
            $userFile->hardDelete();
            $this->flashToastSuccess("Archivo eliminado");
            $this->redirect(route('files.index'));
        } else {
            $this->toastError("Sin autorización");
        }
    }

    private function userFile(): UserFile
    {
        return $this->cachedUserFile ??= UserFile::with(['media'])->findOrFail($this->userFileId);
    }
}
