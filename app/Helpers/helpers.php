<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('can')) {
    function can($permission): bool
    {
        return Auth::user()?->can($permission) ?? false;
    }
}

if (!function_exists('cannot')) {
    function cannot($permission): bool
    {
        return !can($permission);
    }
}
