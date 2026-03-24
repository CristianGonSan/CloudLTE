<?php

namespace App\View\Components\Form;

use Illuminate\Contracts\View\View;
use JeroenNoten\LaravelAdminLte\View\Components\Form\Select;

class SelectWireIgnore extends Select
{
    public function render(): View
    {
        return view('components.form.select-wire-ignore');
    }
}
