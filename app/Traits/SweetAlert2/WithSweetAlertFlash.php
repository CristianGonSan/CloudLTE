<?php

namespace App\Traits\SweetAlert2;

trait WithSweetAlertFlash
{
    protected function flashAlert(
        string $theme,
        string $message = '',
        ?string $title = null,
        array $options = []
    ): void {
        $default = $this->getThemeF($theme);

        $config = [
            'title' => $title ?? $default['title'],
            'text'  => $message,
            'icon'  => $default['icon'],
            ...$options,
        ];

        session()->flash('sweetalert2_flash', $config);
    }

    protected function flashToast(
        string $theme,
        string $message,
        ?string $title = null,
        array $options = []
    ): void {
        $this->flashAlert(
            $theme,
            $message,
            $title,
            [
                'toast' => true,
                'position' => 'top-end',
                'timer' => 4000,
                'showConfirmButton' => false,
                'customClass' => [
                    'popup' => 'custom-toast-position',
                ],
                ...$options,
            ]
        );
    }

    /**
     * @return array{icon: string, title: string}
     */
    private function getThemeF(string $theme): array
    {
        return match ($theme) {
            'success' => [
                'icon' => 'success',
                'title' => 'Éxito',
            ],
            'error' => [
                'icon' => 'error',
                'title' => 'Error',
            ],
            'warning' => [
                'icon' => 'warning',
                'title' => 'Advertencia',
            ],
            'info' => [
                'icon' => 'info',
                'title' => 'Información',
            ],
            'question' => [
                'icon' => 'question',
                'title' => 'Confirmación',
            ],
            default => [
                'icon' => 'success',
                'title' => 'Éxito',
            ],
        };
    }
}
