<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class UserShow extends Component
{
    use Toast, FlashToast;

    #[Locked]
    public int $userId;

    public function mount(int $userId): void
    {
        $this->userId = $userId;
    }

    public function render(): View
    {
        return view('livewire.admin.users.user-show', [
            'user' => $this->user()
        ]);
    }

    public function toggleActive(): void
    {
        if (cannot('users.toggle')) {
            $this->toastError(
                'No tienes permiso para realizar esta acción',
            );
            return;
        }

        $this->toastSuccess(
            $this->user()->toggleActive()
                ? 'Usuario activado'
                : 'Usuario desactivado'
        );
    }

    public function delete(): void
    {
        if (cannot('users.delete')) {
            $this->toastError(
                'No tienes permiso para realizar esta acción',
            );
            return;
        }

        $user = $this->user();

        if ($user->isInUse()) {
            $this->toastError(
                'No se puede eliminar: el usuario está en uso'
            );
        } else {
            $user->delete();
            $this->flashToastSuccess('Usuario eliminado');
            redirect()->route('admin.users.index');
        }
    }

    private ?User $user = null;

    private function user(): User
    {
        return $this->user ??= User::findOrFail($this->userId);
    }
}
