<?php

namespace App\Enums\Document;

enum SignatoryStatus: string
{
    case Pending  = 'pending';  // En bandeja del firmante, esperando accion.
    case Signed   = 'signed';   // El firmante acepto y firmo el documento.
    case Rejected = 'rejected'; // El firmante rechazo firmar.
    case Canceled = 'canceled'; // La asignacion fue cancelada: por el propietario directamente
    // o como consecuencia del rechazo de otro firmante.
    // El motivo queda registrado en DocumentActivity.

    public function label(): string
    {
        return match ($this) {
            self::Pending  => 'Pendiente',
            self::Signed   => 'Firmado',
            self::Rejected => 'Rechazado',
            self::Canceled => 'Cancelado',
        };
    }

    /**
     * Indica si el firmante aun puede actuar sobre su asignacion.
     */
    public function canAct(): bool
    {
        return $this === self::Pending;
    }
}
