<?php

namespace App\Livewire\Auth\Account;

use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class SignatureUpload extends Component
{
    use WithFileUploads, Toast;

    public ?TemporaryUploadedFile $file = null;

    public function render(): View
    {
        return view('livewire.auth.account.signature-upload');
    }

    public function updatedFile(): void
    {
        $this->validateOnly('file');
    }

    #[On('openComponentSignatureUpload')]
    public function openModal(): void
    {
        $this->resetForm();
        $this->dispatch('showModalSignatureUpload');
    }

    #[On('closeComponentSignatureUpload')]
    public function closeModal(): void
    {
        $this->resetForm();
        $this->dispatch('hideModalSignatureUpload');
    }

    public function save(): void
    {
        $this->validate();

        $user = Auth::user();

        // Reemplaza la firma anterior si ya existe
        $user->clearMediaCollection('signature');

        $file         = $this->file;
        $originalName = $file->getClientOriginalName();

        $user->addMedia($file->getRealPath())
            ->usingName(pathinfo($originalName, PATHINFO_FILENAME))
            ->usingFileName($originalName)
            ->toMediaCollection('signature', 'local');

        $this->toastSuccess('Firma subida correctamente.');
        $this->resetForm();
        $this->dispatch('hideModalSignatureUpload');
    }

    private function resetForm(): void
    {
        $this->reset(['file']);
        $this->resetValidation();
    }

    protected function rules(): array
    {
        return [
            'file' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:2048'],
        ];
    }
}
