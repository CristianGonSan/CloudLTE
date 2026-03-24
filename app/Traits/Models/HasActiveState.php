<?php

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Builder;

trait HasActiveState
{
    /**
     * Marca el modelo como inactivo.
     */
    public function deactivate(): bool
    {
        $this->is_active = false;
        return $this->save();
    }

    /**
     * Marca el modelo como activo.
     */
    public function activate(): bool
    {
        $this->is_active = true;
        return $this->save();
    }

    /**
     * Comprueba si el modelo está inactivo.
     */
    public function isInactive(): bool
    {
        return !$this->is_active;
    }

    /**
     * Comprueba si el modelo está activo.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Scope: modelos activos.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: modelos inactivos.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    /**
     * Icono de estado.
     */
    public function getActiveIconClass(): string
    {
        return $this->is_active
            ? "fa-solid fa-circle-check text-success"
            : "fa-solid fa-circle-xmark text-secondary";
    }

    /**
     * Alterna el estado activo/inactivo.
     */
    public function toggleActive(): bool
    {
        $this->is_active = !$this->is_active;
        $this->save();
        return $this->is_active;
    }

    /**
     * Alterna el estado activo/inactivo. Y retorna un valor.
     */
    public function toggleActiveWithReturn($ifActive, $ifInactive): mixed
    {
        return $this->toggleActive() ? $ifActive : $ifInactive;
    }
}
