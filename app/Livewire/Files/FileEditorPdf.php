<?php

namespace App\Livewire\Files;

use App\Models\UserFile;
use App\Traits\SweetAlert2\FlashToast;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class FileEditorPdf extends Component
{
    use WithFileUploads, FlashToast;

    #[Locked]
    public int $userFileId;

    public ?TemporaryUploadedFile $file = null;

    public function mount(int $userFileId): void
    {
        $this->userFileId = $userFileId;
    }

    public function render()
    {
        return view('livewire.files.file-editor-pdf', [
            'userFile' => $this->userFile(),
        ]);
    }

    public function save(): void
    {
        $this->validate([
            'file' => ['required', 'file', 'mimes:pdf', 'max:51200'],
        ]);

        $userFile = $this->userFile();

        $originalFile   = $userFile->getFile();
        $name           = $originalFile->name;
        $fileName       = $originalFile->file_name;

        $userFile
            ->addMedia($this->file->getRealPath())
            ->usingName($name)
            ->usingFileName($fileName)
            ->toMediaCollection('file', 'local');

        $userFile->edited_at = now();
        $userFile->version++;
        $userFile->save();

        $this->flashToastSuccess('Archivo editado');
        redirect()->route('root');
    }

    // ── Privado ───────────────────────────────────────────────────────────────

    private ?UserFile $fileUser = null;

    private function userFile(): UserFile
    {
        return $this->fileUser ??= UserFile::findOrFail($this->userFileId);
    }
}
