<?php

namespace App\Livewire\UserFiles\Signatures;

use App\Enums\SignatoryStatus;
use App\Enums\SignatureRequestStatus;
use App\Models\SignatureRequest;
use App\Traits\SweetAlert2\Livewire\Toast;
use DB;
use Livewire\Attributes\Locked;
use Livewire\Component;

class SignatureRequestShow extends Component
{
    use Toast;

    #[Locked]
    public int $signatureRequestId;

    private ?SignatureRequest $cachedSignatureRequest = null;

    public function mount(int $signatureRequestId): void
    {
        $this->signatureRequestId = $signatureRequestId;
    }

    public function render()
    {
        return view('livewire.user-files.signatures.signature-request-show', [
            'signatureRequest' => $this->signatureRequest(),
        ]);
    }

    public function cancel(): void
    {
        DB::transaction(function () {
            $signatureRequest = $this->signatureRequest();
            $signatureRequest->markAs(SignatureRequestStatus::Cancelled);

            $signatories = $signatureRequest->signatories;

            foreach ($signatories as $signatory) {
                if ($signatory->status === SignatoryStatus::Pending) {
                    $signatory->markAs(SignatoryStatus::Cancelled);
                }
            }

            $this->toastSuccess('Solicitud cancelada');
        });
    }

    private function signatureRequest(): SignatureRequest
    {
        return $this->cachedSignatureRequest ??= SignatureRequest::with([
            'userFile',
            'signatories.user',
        ])->findOrFail($this->signatureRequestId);
    }
}
