<?php

namespace App\Traits\SweetAlert2\Livewire;

trait Toast
{
    public function toastSuccess(string $text, ?string $title = null): void
    {
        $this->dispatchToast($text, $title, 'success');
    }

    public function toastError(string $text, ?string $title = null): void
    {
        $this->dispatchToast($text, $title, 'error');
    }

    public function toastWarning(string $text, ?string $title = null): void
    {
        $this->dispatchToast($text, $title, 'warning');
    }

    public function toastInfo(string $text, ?string $title = null): void
    {
        $this->dispatchToast($text, $title, 'info');
    }

    public function toastQuestion(string $text, ?string $title = null): void
    {
        $this->dispatchToast($text, $title, 'question');
    }

    protected function dispatchToast(string $text, ?string $title = null, string $icon): void
    {
        $hasTitle = !empty($title);
        $config = [
            'title' => $hasTitle ? $title : $text,
            'text'  => $hasTitle ? $text : null,
            'icon'  => $icon,
            'toast' => true,
            'position' => 'top-end',
            'timer' => $icon === "error" ? 4000 : 3000,
            'showConfirmButton' => false,
            'timerProgressBar' => true,
            'customClass' => [
                'popup' => 'custom-toast-position'
            ]
        ];

        $this->js('Swal.fire(' . json_encode($config) . ');');
    }
}
