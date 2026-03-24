<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserEdit extends Component
{
    use Toast, FlashToast;

    #[Locked]
    public int $userId;

    public string $name;
    public string $email;
    public string $password;
    public string $password_confirmation;
    public array $roles = [];
    public array $selectedRoles = [];

    public function mount(int $userId): void
    {
        $this->userId   = $userId;
        $user           = $this->user();

        $this->name     = $user->name;
        $this->email    = $user->email;

        foreach (Role::orderBy('name')->get() as $role) {
            $this->roles[$role->name] = $role->name;
        }

        foreach ($user->getRoleNames() as $role) {
            $this->selectedRoles[] = $role;
        }
    }

    public function render(): View
    {
        return view('livewire.admin.users.user-edit');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:191', Rule::unique('users')->ignore($this->userId)],
            'selectedRoles' => ['nullable', 'array']
        ]);

        $user = $this->user();
        $user->update($validated);

        $user->syncRoles($validated['selectedRoles'] ?? []);

        $this->toastSuccess('Información actualizada');
    }

    public function changePassword(): void
    {
        $this->validate([
            'password' => ['required', 'min:8', 'max:191', 'confirmed'],
        ]);

        $this->user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset([
            'password',
            'password_confirmation'
        ]);

        $this->toastSuccess('Contraseña actualizada.');
    }

    private ?User $user = null;

    private function user(): User
    {
        return $this->user ??= User::findOrFail($this->userId);
    }
}
