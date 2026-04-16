<?php

namespace App\Livewire\UserFiles\Signatures;

use App\Models\SignatureRequest;
use App\Models\User;
use App\Models\UserFile;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Str;

class SignatureRequestCreate extends Component
{
    use Toast, FlashToast;

    #[Locked]
    public int $userFileId;

    public ?string $message = null;

    public array $signatures = [];

    private ?UserFile $cachedUserFile = null;

    public function mount(int $userFileId): void
    {
        $this->userFileId = $userFileId;
    }

    public function render()
    {
        return view('livewire.user-files.signatures.signature-request-create', [
            'userFile' => $this->userFile(),
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
            $signatureRequest = SignatureRequest::create([
                'user_file_id' => $this->userFileId,
                'message'      => $validated['message'],
            ]);

            foreach ($validated['signatures'] ?? [] as $signature) {
                $signatureRequest->signatories()->create([
                    'user_id'             => $signature['id'],
                    'required_signatures' => $signature['required_signatures'],
                ]);
            }

            $this->flashToastSuccess('Solicitud de firma creada correctamente.');
            $this->redirect(route('files.signatures.show', [$this->userFileId, $signatureRequest->id]));
        });
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
            'userFileId'                       => ['required', Rule::exists('user_files', 'id')],
            'message'                          => ['nullable', 'string', 'max:500'],
            'signatures.*.id'                  => ['required', Rule::exists('users', 'id')->where('is_active', true)],
            'signatures.*.required_signatures' => ['required', 'integer', 'min:1', 'max:16'],
        ];
    }

    private function userFile(): UserFile
    {
        return $this->cachedUserFile ??= UserFile::findOrFail($this->userFileId);
    }
}
