<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserCreate extends Component
{
    use Toast, FlashToast;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public array $roles = [];
    public array $selectedRoles = [];

    public bool $createAnother = false;

    public function mount(): void
    {
        $this->roles = Role::orderBy('name', 'desc')
            ->pluck('name', 'name')->toArray();
    }

    public function render(): View
    {
        return view('livewire.admin.users.user-create');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:191', Rule::unique('users', 'email')],
            'password'      => ['required', 'min:8', 'max:64', 'confirmed'],
            'selectedRoles' => ['nullable', 'array']
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
        ]);

        $user->syncRoles($validated['selectedRoles'] ?? []);

        if ($this->createAnother) {
            $this->reset([
                'name',
                'email',
                'password',
                'password_confirmation',
                'selectedRoles'
            ]);

            $this->dispatch('reset');
            $this->toastSuccess('Usuario creado');
        } else {
            $this->flashToastSuccess('Usuario creado');
            redirect()->route('admin.users.show', $user->id);
        }
    }
}
