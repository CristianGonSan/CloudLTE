<?php

namespace App\Enums;

enum SignatoryStatus: string
{
    case Pending  = 'pending';
    case Signed   = 'signed';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending   => 'Pendiente',
            self::Signed    => 'Firmado',
            self::Rejected  => 'Rechazado',
            self::Cancelled => 'Cancelado',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending   => 'badge-warning',
            self::Signed    => 'badge-success',
            self::Rejected  => 'badge-danger',
            self::Cancelled => 'badge-secondary',
        };
    }
}
