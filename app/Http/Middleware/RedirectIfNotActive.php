<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotActive
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::check() && !Auth::user()->is_active) {
            Auth::logout(); // Cerrar sesión para evitar conflictos
            return redirect()->route('auth.disabled'); // Redirigir a la página de cuenta desactivada
        }

        return $next($request);
    }
}
