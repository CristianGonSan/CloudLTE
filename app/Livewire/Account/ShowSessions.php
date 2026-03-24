<?php

namespace App\Livewire\Account;

use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Str;

class ShowSessions extends Component
{
    use Toast;

    public $currentPassword;

    public function render(): View
    {
        return view('livewire.account.show-sessions', [
            'sessions' => $this->getSessions()
        ]);
    }

    public function logoutOtherDevices(): void
    {
        $user = Auth::user();

        if (Hash::check($this->currentPassword, $user->password)) {
            Auth::logoutOtherDevices($this->currentPassword);
            $this->deleteSessions();

            $user->setRememberToken(Str::random(60));
            $user->save();

            $this->toastSuccess('Sesiones Cerradas');
        } else {
            $this->toastError('La contraseña actual es icorrecta.');
        }

        $this->reset('currentPassword');
    }

    private function getSessions(): Collection
    {
        $icons = [
            'mobile'    => 'fa-solid fa-mobile-screen-button',
            'tablet'    => 'fa-solid fa-tablet-screen-button',
            'desktop'   => 'fa-solid fa-desktop'
        ];

        return DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) use ($icons) {
                return [
                    'id'                => $session->id,
                    'ip_address'        => $session->ip_address,
                    'user_agent'        => $session->user_agent,
                    'last_activity'     => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    'is_current_device' => $session->id === Session::getId(),
                    'device_type'       => $type = $this->getDeviceType($session->user_agent),
                    'icon'              => $icons[$type]
                ];
            });
    }

    private function deleteSessions(): void
    {
        DB::table('sessions')
            ->where('id', '!=', Session::getId())
            ->delete();
    }

    private function getDeviceType($userAgent): string
    {
        $mobileKeywords = ['Mobile', 'Android', 'iPhone', 'iPod', 'BlackBerry', 'Windows Phone', 'Opera Mini', 'IEMobile'];
        $tabletKeywords = ['iPad', 'Android', 'Kindle', 'Silk', 'Tablet'];

        foreach ($tabletKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false && stripos($userAgent, 'Mobile') === false) {
                return 'tablet';
            }
        }

        foreach ($mobileKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                return 'mobile';
            }
        }

        return 'desktop';
    }
}
