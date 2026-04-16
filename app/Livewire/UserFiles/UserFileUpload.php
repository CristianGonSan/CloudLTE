<?php

namespace App\Livewire\UserFiles;

use App\Rules\FileSupport;
use Exception;
use App\Models\UserFile;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class UserFileUpload extends Component
{
    use WithFileUploads, Toast;

    public ?TemporaryUploadedFile $file = null;

    public string $description = '';

    public function render(): View
    {
        return view('livewire.user-files.user-file-upload');
    }

    public function updatedFile(): void
    {
        $this->validateOnly('file');
    }

    #[On('openComponentUserFileUpload')]
    public function openModal(): void
    {
        $this->resetForm();
        $this->dispatch('showModalUserFileUpload');
    }

    #[On('closeComponentUserFileUpload')]
    public function closeModal(): void
    {
        $this->dispatch('hideModalUserFileUpload');
    }

    public function save(): void
    {
        $validated = $this->validate();

        try {
            DB::transaction(function () use ($validated) {
                $document = UserFile::create([
                    'user_id'       => Auth::id(),
                    'description'   => $validated['description']
                ]);

                $file = $this->file;
                $originalName = $file->getClientOriginalName();

                $document->addMedia($file)
                    ->usingName(pathinfo($originalName, PATHINFO_FILENAME))
                    ->usingFileName($originalName)
                    ->toMediaCollection('file', 'local');
            });

            $this->toastSuccess('Archivo subido correctamente.');
            $this->resetForm();
            $this->dispatch('fileSaved');
            //$this->closeModal();
        } catch (Exception $e) {
            $this->toastError('Error al subir: ' . $e->getMessage());
        }
    }

    private function resetForm(): void
    {
        $this->reset(['file', 'description']);
        $this->resetValidation();
    }

    public function rules(): array
    {
        return [
            'file'          => ['required', 'file', 'max:10240', new FileSupport()],
            'description'   => ['nullable', 'string', 'max:255'],
        ];
    }
}
