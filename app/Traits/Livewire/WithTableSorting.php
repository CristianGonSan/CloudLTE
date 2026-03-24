<?php

namespace App\Traits\Livewire;

use Illuminate\View\View;

/**
 * Trait WithTableSorting
 *
 * Utilidades para tablas con ordenamiento en componentes Livewire.
 *
 * REQUISITOS DEL COMPONENTE QUE USE ESTE TRAIT:
 *
 * Propiedades públicas requeridas:
 *
 * @property string $sortColumn     Columna actualmente usada para ordenar
 * @property string $sortDirection  Dirección del ordenamiento: 'asc' | 'desc'
 * @property array  $theadConfig    Configuración del encabezado de la tabla
 *
 * ESTRUCTURA ESPERADA DE $theadConfig:
 *
 * [
 *     [
 *         'label'  => 'Nombre',
 *         'column' => 'name',     // opcional, habilita ordenamiento
 *         'align'  => 'left',     // opcional (left | center | right)
 *         'style'  => 'width:20%' // opcional
 *     ],
 *     ...
 * ]
 *
 * HOOKS OPCIONALES:
 *
 * - afterSortChanged(): Se ejecuta después de cambiar el ordenamiento
 *   Útil para reiniciar la paginación o disparar queries
 */
trait WithTableSorting
{
    /**
     * Valida que el componente cumple con los requisitos del trait.
     */
    protected function validateTableSortingRequirements(): void
    {
        $required = ['sortColumn', 'sortDirection', 'theadConfig'];

        foreach ($required as $property) {
            if (!property_exists($this, $property)) {
                throw new \RuntimeException(
                    \sprintf(
                        'El componente %s debe tener la propiedad pública $%s para usar %s',
                        static::class,
                        $property,
                        self::class
                    )
                );
            }
        }
    }

    /**
     * Alterna la dirección del ordenamiento o establece una nueva columna.
     *
     * - Si la columna es la actual, invierte la dirección.
     * - Si es una nueva columna, usa orden descendente por defecto.
     * - Reinicia la paginación si el componente la utiliza.
     */
    public function toggleSortDirection(string $column): void
    {
        if ($this->isCurrentColumn($column)) {
            $this->sortDirection = $this->ascOrDesc('desc', 'asc');
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'desc';
        }

        if (method_exists($this, 'afterSortChanged')) {
            $this->afterSortChanged();
        }
    }

    /**
     * Devuelve un valor según el estado de ordenamiento de una columna.
     *
     * Útil para definir íconos o estilos en el encabezado.
     */
    public function checkColumnSort(string $column, $ifNot, $ifAsc, $ifDesc): mixed
    {
        if (!$this->isCurrentColumn($column)) {
            return $ifNot;
        }

        return $this->ascOrDesc($ifAsc, $ifDesc);
    }

    /**
     * Devuelve un valor dependiendo de la dirección actual del ordenamiento.
     */
    public function ascOrDesc($ifAsc, $ifDesc): mixed
    {
        return $this->sortDirection === 'asc'
            ? $ifAsc
            : $ifDesc;
    }

    /**
     * Indica si la columna dada es la actualmente usada para ordenar.
     */
    public function isCurrentColumn(string $column): bool
    {
        return $this->sortColumn === $column;
    }

    /**
     * Devuelve la vista del encabezado de la tabla junto con su configuración.
     *
     * La vista espera la variable $theadConfig con la estructura documentada
     * en la cabecera de este trait.
     *
     * @throws \RuntimeException Si faltan propiedades requeridas
     */
    public function thead(): View
    {
        $this->validateTableSortingRequirements();

        return view('partials.livewire.table-tools.thead', [
            'theadConfig' => $this->theadConfig,
        ]);
    }
}
