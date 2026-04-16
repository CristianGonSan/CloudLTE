<?php

namespace App\Livewire\UserFiles\Signatures;

use App\Models\SignatureRequest;
use App\Models\User;
use App\Traits\SweetAlert2\Livewire\Toast;
use DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class SignatureRequestEdit extends Component
{
    use Toast;

    #[Locked]
    public int $signatureRequestId;

    public ?string $message = null;

    public array $signatures = [];

    private ?SignatureRequest $cachedSignatureRequest = null;

    public function mount(int $signatureRequestId): void
    {
        $this->signatureRequestId = $signatureRequestId;

        $signatureRequest = $this->signatureRequest();

        $this->message = $signatureRequest->message;

        $this->signatures = $signatureRequest->signatories()
            ->with('user')
            ->get()
            ->map(fn($signatory) => [
                'id'                  => $signatory->user->id,
                'name'                => $signatory->user->name,
                'email'               => $signatory->user->email,
                'required_signatures' => $signatory->required_signatures,
            ])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.user-files.signatures.signature-request-edit', [
            'userFile' => $this->signatureRequest()->userFile,
        ]);
    }

    public function save(): void
    {
        if (empty($this->signatures)) {
            $this->toastError('Debe seleccionar al menos un usuario.');
            return;
        }

        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            $signatureRequest = $this->signatureRequest();

            $signatureRequest->update([
                'message' => $validated['message'],
            ]);

            $signatureRequest->signatories()->delete();

            foreach ($validated['signatures'] ?? [] as $signature) {
                $signatureRequest->signatories()->create([
                    'user_id'             => $signature['id'],
                    'required_signatures' => $signature['required_signatures'],
                ]);
            }
        });

        $this->toastSuccess('Solicitud de firma actualizada correctamente.');
    }

    public function addSignature(int $userId): void
    {
        foreach ($this->signatures as $signature) {
            if ($signature['id'] == $userId) {
                $this->toastWarning('El usuario ya fue agregado.');
                return;
            }
        }

        $user = User::find($userId);

        if (!$user) {
            $this->toastError('Usuario no encontrado.');
            return;
        }

        $this->signatures[] = [
            'id'                  => $user->id,
            'name'                => $user->name,
            'email'               => $user->email,
            'required_signatures' => 1,
        ];

        $this->toastSuccess('Usuario agregado correctamente.');
    }

    public function remove(int $index): void
    {
        unset($this->signatures[$index]);

        $this->toastSuccess('Usuario eliminado correctamente.');
    }

    protected function rules(): array
    {
        return [
            'signatureRequestId'               => ['required', Rule::exists('signature_requests', 'id')],
            'message'                          => ['nullable', 'string', 'max:500'],
            'signatures.*.id'                  => ['required', Rule::exists('users', 'id')->where('is_active', true)],
            'signatures.*.required_signatures' => ['required', 'integer', 'min:1', 'max:16'],
        ];
    }

    private function signatureRequest(): SignatureRequest
    {
        return $this->cachedSignatureRequest ??= SignatureRequest::with('userFile')->findOrFail($this->signatureRequestId);
    }
}
