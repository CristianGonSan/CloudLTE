<?php

namespace App\Traits\SweetAlert2;

trait FlashToast
{
    public function flashToastSuccess(string $text, ?string $title = null): void
    {
        $this->flashToast($text, $title, 'success');
    }

    public function flashToastError(string $text, ?string $title = null): void
    {
        $this->flashToast($text, $title, 'error');
    }

    public function flashToastWarning(string $text, ?string $title = null): void
    {
        $this->flashToast($text, $title, 'warning');
    }

    public function flashToastInfo(string $text, ?string $title = null): void
    {
        $this->flashToast($text, $title, 'info');
    }

    public function flashToastQuestion(string $text, ?string $title = null): void
    {
        $this->flashToast($text, $title, 'question');
    }

    protected function flashToast(string $text, ?string $title = null, string $icon): void
    {
        $hasTitle = !empty($title);
        session()->flash('sweetalert2_flash', [
            'title' => $hasTitle ? $title : $text,
            'text'  => $hasTitle ? $text : null,
            'icon'  => $icon,
            'toast' => true,
            'position' => 'top-end',
            'timer' => $icon === "error" ? 5000 : 4000,
            'showConfirmButton' => false,
            'timerProgressBar' => true,
            'customClass' => [
                'popup' => 'custom-toast-position'
            ]
        ]);
    }
}
