<?php

namespace App\Livewire\UserFiles\Signatures;

use App\Enums\SignatoryStatus;
use App\Enums\SignatureRequestStatus;
use App\Models\Signatory;
use App\Models\UserFile;
use App\Traits\SweetAlert2\FlashToast;
use DB;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class PdfSigner extends Component
{
    use WithFileUploads, FlashToast;

    #[Locked]
    public int $signatoryId;

    public ?TemporaryUploadedFile $file = null;

    public function mount(int $signatoryId): void
    {
        $this->signatoryId = $signatoryId;
    }

    public function render(): View
    {
        return view('livewire.user-files.signatures.pdf-signer', [
            'signatory' => $this->signatory(),
        ]);
    }

    public function sign(): void
    {
        $this->validate([
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $signatory          = $this->signatory();
        $signatureRequest   = $signatory->signatureRequest;
        $userFile           = $signatureRequest->userFile;

        $originalFile       = $userFile->getFile();
        $name               = $originalFile->name;
        $fileName           = $originalFile->file_name;

        $userFile
            ->addMedia($this->file->getRealPath())
            ->usingName($name)
            ->usingFileName($fileName)
            ->toMediaCollection('file', 'local');

        $signatory->markAs(SignatoryStatus::Signed);

        $allSigned = true;

        foreach ($signatureRequest->signatories as $signatory) {
            if ($signatory->status !== SignatoryStatus::Signed) {
                $allSigned = false;
                break;
            }
        }

        if ($allSigned) {
            $signatureRequest->markAs(SignatureRequestStatus::Signed);
        }

        $this->flashToastSuccess('Firma guardada');
        $this->redirect(route('files.signatures.show', [$userFile->id, $signatureRequest->id]));
    }

    public function reject(): void
    {
        DB::transaction(function () {
            $signatory          = $this->signatory();
            $signatureRequest   = $signatory->signatureRequest;
            $userFile           = $signatureRequest->userFile;

            $signatureRequest->markAs(SignatureRequestStatus::Rejected);
            $signatory->markAs(SignatoryStatus::Rejected);

            foreach ($signatureRequest->signatories as $sign) {
                if ($sign->status === SignatoryStatus::Pending) {
                    $sign->markAs(SignatoryStatus::Cancelled);
                }
            }

            $this->flashToastSuccess('Has rechazado firmar');
            $this->redirect(route('files.signatures.show', [$userFile->id, $signatureRequest->id]));
        });
    }

    private ?Signatory $signatory = null;

    private function signatory(): Signatory
    {
        return $this->signatory ??= Signatory::findOrFail($this->signatoryId);
    }
}
