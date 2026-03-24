<?php

namespace App\Traits\SweetAlert2;

trait FlashAlert
{
    public function flashAlertSuccess(string $text = '', string $title = 'Éxito'): void
    {
        $this->flashAlert($text, $title, 'success');
    }

    public function flashAlertError(string $text = '', string $title = 'Error'): void
    {
        $this->flashAlert($text, $title, 'error');
    }

    public function flashAlertWarning(string $text = '', string $title = 'Advertencia'): void
    {
        $this->flashAlert($text, $title, 'warning');
    }

    public function flashAlertInfo(string $text = '', string $title = 'Información'): void
    {
        $this->flashAlert($text, $title, 'info');
    }

    public function flashAlertQuestion(string $text = '', string $title = '¿Estás seguro?'): void
    {
        $this->flashAlert($text, $title, 'question');
    }

    protected function flashAlert(string $text, string $title, string $icon): void
    {
        session()->flash('sweetalert2_flash', [
            'title' => $title,
            'text'  => $text,
            'icon'  => $icon,
        ]);
    }
}
