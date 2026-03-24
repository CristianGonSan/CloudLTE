<?php

namespace App\Traits\SweetAlert2\Livewire;

trait WithSweetAlert
{
    protected function dispatchAlert(string $theme, string $message = '', ?string $title = null, array $options = []): void
    {
        $default = $this->getTheme($theme);

        $config = [
            'title' => $title ?? $default['title'],
            'text'  => $message,
            'icon'  => $default['icon'],
            ...$options
        ];

        $this->dispatch('sweetalert2', config: $config);
    }

    protected function dispatchToast(string $theme, string $message, ?string $title = null, array $options = []): void
    {
        $this->dispatchAlert(
            $theme,
            $message,
            $title,
            [
                'toast' => true,
                'position' => 'top-end',
                'timer' => 3000,
                'showConfirmButton' => false,
                'customClass' => [
                    'popup' => 'custom-toast-position'
                ],
                ...$options
            ]
        );
    }

    /**
     * @return array{icon: string, title: string}
     */
    private function getTheme(string $theme): array
    {
        $defaultTheme = match ($theme) {
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

        return $defaultTheme;
    }
}
