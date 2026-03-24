<?php

namespace App\Traits\SweetAlert2\Livewire;

trait Alert
{
    public function alertSuccess(string $text = '', string $title = 'Éxito'): void
    {
        $this->dispatchAlert($text, $title, 'success');
    }

    public function alertError(string $text = '', string $title = 'Error'): void
    {
        $this->dispatchAlert($text, $title, 'error');
    }

    public function alertWarning(string $text = '', string $title = 'Advertencia'): void
    {
        $this->dispatchAlert($text, $title, 'warning');
    }

    public function alertInfo(string $text = '', string $title = 'Información'): void
    {
        $this->dispatchAlert($text, $title, 'info');
    }

    public function alertQuestion(string $text = '', string $title = '¿Estás seguro?'): void
    {
        $this->dispatchAlert($text, $title, 'question');
    }

    protected function dispatchAlert(string $text, string $title, string $icon): void
    {
        $config = [
            'title' => $title,
            'text'  => $text,
            'icon'  => $icon
        ];

        $this->js('Swal.fire(' . json_encode($config) . ');');
    }
}
