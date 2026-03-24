<?php

namespace App\Livewire\Account;

use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Component;

class ChangePassword extends Component
{
    use Toast;

    public $current_password;
    public $password;
    public $password_confirmation;

    public function render(): View
    {
        return view('livewire.account.change-password');
    }

    public function update(): void
    {
        $this->validate([
            'current_password'  => ['required'],
            'password'          => ['required', 'min:8', 'max:64', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->toastError('La contraseña actual es icorrecta.');
        } else {
            $user->update([
                'password' => Hash::make($this->password),
            ]);

            $this->toastSuccess('Contraseña Actualizada');
        }

        $this->reset(['current_password', 'password', 'password_confirmation']);
    }
}
