<?php

namespace App\Enums\Document;

enum DocumentStatus: string
{
    case Draft     = 'draft';     // Subido pero no enviado a firmas ni completado.
    case Pending   = 'pending';   // Enviado, esperando que todos los firmantes actuen.
    case Signed    = 'signed';    // Todos los firmantes firmaron.
    case Rejected  = 'rejected';  // Al menos un firmante rechazo; la solicitud queda bloqueada.
    case Canceled  = 'canceled';  // El propietario cancelo la solicitud de firmas.
    case Completed = 'completed'; // Cerrado sin requerir firmas.

    public function label(): string
    {
        return match ($this) {
            self::Draft     => 'Borrador',
            self::Pending   => 'Pendiente',
            self::Signed    => 'Firmado',
            self::Rejected  => 'Rechazado',
            self::Canceled  => 'Cancelado',
            self::Completed => 'Completado',
        };
    }

    /**
     * Indica si el documento admite nuevas solicitudes de firma.
     */
    public function canRequestSignatures(): bool
    {
        return match ($this) {
            self::Draft => true,
            default     => false,
        };
    }

    /**
     * Indica si el documento puede ser editado (nombre, categoria, archivo).
     */
    public function canBeEdited(): bool
    {
        return match ($this) {
            self::Draft => true,
            default     => false,
        };
    }

    /**
     * Indica si el documento puede ser eliminado.
     */
    public function canBeDeleted(): bool
    {
        return match ($this) {
            self::Draft,
            self::Completed,
            self::Canceled,
            self::Rejected  => true,
            default         => false,
        };
    }
}
