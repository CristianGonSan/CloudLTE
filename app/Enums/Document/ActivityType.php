<?php

namespace App\Enums\Document;

enum ActivityType: string
{
    case Uploaded                 = 'uploaded';                     // Archivo subido por primera vez.
    case FileReplaced             = 'file_replaced';                // Archivo reemplazado por una nueva version.
    case Renamed                  = 'renamed';                      // Nombre o categoria del documento modificados.
    case SignatoryAdded           = 'signatory_added';              // Firmante agregado a la solicitud.
    case SignatoryRemoved         = 'signatory_removed';            // Firmante removido de la solicitud por el propietario.
    case SignatureSigned          = 'signature_signed';             // Un firmante completo el proceso de firma.
    case SignatureRejected        = 'signature_rejected';           // Un firmante rechazo firmar.
    case SignatureRequestCanceled = 'signature_request_canceled';   // Propietario cancelo toda la solicitud de firmas.
    case Completed                = 'completed';                    // Documento marcado como completado sin requerir firmas.

    public function label(): string
    {
        return match ($this) {
            self::Uploaded                 => 'Archivo subido',
            self::FileReplaced             => 'Archivo reemplazado',
            self::Renamed                  => 'Documento renombrado',
            self::SignatoryAdded           => 'Firmante agregado',
            self::SignatoryRemoved         => 'Firmante removido',
            self::SignatureSigned          => 'Documento firmado',
            self::SignatureRejected        => 'Firma rechazada',
            self::SignatureRequestCanceled => 'Solicitud de firmas cancelada',
            self::Completed                => 'Documento completado',
        };
    }
}
