<?php

namespace App\Livewire\Admin\Roles;

use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleCreate extends Component
{
    use Toast, FlashToast;

    public string $name;

    public array $selectedPermissions = [];

    public bool $createAnother = false;

    public function render(): View
    {
        return view('livewire.admin.roles.role-create', [
            'permissions' => $this->getTranslatedPermissions()
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'                  => ['required', 'string', 'max:64', Rule::unique('roles', 'name')],
            'selectedPermissions'   => ['nullable', 'array']
        ]);

        $role = Role::create($validated);

        $role->syncPermissions($validated['selectedPermissions'] ?? []);

        if ($this->createAnother) {
            $this->reset(['name', 'selectedPermissions']);

            $this->dispatch('reset');
            $this->toastSuccess('Rol creado.');
        } else {
            $this->flashToastSuccess('Rol creado.');
            redirect()->route('admin.roles.show', $role->id);
        }
    }

    private function getTranslatedPermissions(): array
    {
        $permissions = [];
        foreach (Permission::orderBy('id')->get() as $p) {
            $langKey = "permissions.{$p->name}";
            $permissions[$p->name] = __($langKey);
        }
        return $permissions;
    }
}
