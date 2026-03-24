<?php

namespace App\View\Components\Form;

use Illuminate\Contracts\View\View;
use JeroenNoten\LaravelAdminLte\View\Components\Form\Input;

class InputWireIgnore extends Input
{
    public function render(): View
    {
        return view('components.form.input-wire-ignore');
    }
}
