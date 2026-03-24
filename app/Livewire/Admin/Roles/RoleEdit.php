<?php

namespace App\Livewire\Admin\Roles;

use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleEdit extends Component
{
    use Toast, FlashToast;

    #[Locked]
    public int $roleId;

    public string $name;
    public array $selectedPermissions = [];


    public function mount(int $roleId): void
    {
        $this->roleId   = $roleId;
        $role           = $this->role();

        $this->name     = $role->name;

        foreach ($role->permissions as $permission) {
            $this->selectedPermissions[] = $permission->name;
        }
    }

    public function render(): View
    {
        return view('livewire.admin.roles.role-edit', [
            'role'        => $this->role(),
            'permissions' => $this->getTranslatedPermissions()
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name'                  => ['required', 'string', 'max:64', Rule::unique('roles', 'name')->ignore($this->roleId)],
            'selectedPermissions'   => ['nullable', 'array']
        ]);

        $role = $this->role();
        $role->update($validated);

        $role->syncPermissions($validated['selectedPermissions'] ?? []);

        $this->toastSuccess('Información actualizada');
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

    private ?Role $role = null;

    private function role(): Role
    {
        return $this->role ??= Role::findOrFail($this->roleId);
    }
}
