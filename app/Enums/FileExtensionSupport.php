<?php

namespace App\Enums;

use Symfony\Component\Mime\MimeTypes;

enum FileExtensionSupport: string
{
    // Documentos Microsoft Office
    case Pdf = 'pdf';
    case Doc = 'doc';
    case Docx = 'docx';
    case Xls = 'xls';
    case Xlsx = 'xlsx';
    case Ppt = 'ppt';
    case Pptx = 'pptx';
    case Txt = 'txt';
    case Rtf = 'rtf';
    case Csv = 'csv';

        // OpenDocument (LibreOffice / OpenOffice)
    case Odt = 'odt';   // Writer (equivalente a .docx)
    case Ods = 'ods';   // Calc   (equivalente a .xlsx)
    case Odp = 'odp';   // Impress (equivalente a .pptx)
    case Odg = 'odg';   // Draw
    case Odf = 'odf';   // Fórmulas

        // Imágenes
    case Jpg = 'jpg';
    case Jpeg = 'jpeg';
    case Png = 'png';
    case Webp = 'webp';
    case Gif = 'gif';
    case Svg = 'svg';
    case Jfif = 'jfif';
    case Bmp = 'bmp';
    case Tiff = 'tiff';
    case Tif = 'tif';
    case Heic = 'heic';
    case Ico = 'ico';

        // Video
    case Mp4 = 'mp4';
    case Avi = 'avi';
    case Mov = 'mov';
    case Mkv = 'mkv';
    case Webm = 'webm';

        // Audio
    case Mp3 = 'mp3';
    case Wav = 'wav';
    case Ogg = 'ogg';
    case Flac = 'flac';
    case Aac = 'aac';

        // Comprimidos
    case Zip = 'zip';
    case Rar = 'rar';
    case SevenZ = '7z';
    case Tar = 'tar';
    case Gz = 'gz';

        // Código / Datos
    case Json = 'json';
    case Xml = 'xml';
    case Html = 'html';
    case Htm = 'htm';

        // SAT — e.firma y Certificados de Sello Digital (CSD)
    case Cer = 'cer';   // Certificado público (.cer)
    case Key = 'key';   // Llave privada cifrada
    case Req = 'req';   // Requerimiento de certificado

        // SAT — Declaraciones y contabilidad electrónica
    case Dec = 'dec';   // Declaraciones fiscales (DeclaraSAT)
    case Diot = 'diot'; // Declaración Informativa de Operaciones con Terceros
    case Xslt = 'xslt'; // Hojas de transformación CFDI

        // IMSS / SUA
    case Sua = 'sua';   // Sistema Único de Autodeterminación (cuotas IMSS)
    case Dis = 'dis';   // Dispersión de nómina IMSS
    case Idse = 'idse'; // IMSS Desde Su Empresa (movimientos afiliatorios)
    case Noi = 'noi';   // Nómina Integral (exportación NOI)

        // INFONAVIT
    case Sin = 'sin';   // Sistema de Información INFONAVIT (aportaciones)

        // STPS / nómina
    case Sar = 'sar';   // Archivos de aportaciones SAR/Afore

        // Contabilidad / ERP (COI, NOI, ASPEL, etc.)
    case Pol = 'pol';   // Pólizas contables (COI ASPEL)
    case Coi = 'coi';   // Catálogo / respaldo COI ASPEL
    case Bdf = 'bdf';   // Balanza de comprobación (exportación)
    case Sae = 'sae';   // Respaldo SAE ASPEL
    case Nomixml = 'nomixml'; // CFDI nómina versión 1.2

    case Unknown = 'unknown';

    public function label(): string
    {
        return match ($this) {
            self::Pdf                               => 'PDF',
            self::Doc, self::Docx                   => 'Word',
            self::Xls, self::Xlsx                   => 'Excel',
            self::Ppt, self::Pptx                   => 'PowerPoint',
            self::Txt                               => 'Texto plano',
            self::Rtf                               => 'RTF',
            self::Csv                               => 'CSV',
            self::Odt                               => 'OpenDocument Texto',
            self::Ods                               => 'OpenDocument Hoja de cálculo',
            self::Odp                               => 'OpenDocument Presentación',
            self::Odg                               => 'OpenDocument Dibujo',
            self::Odf                               => 'OpenDocument Fórmula',
            self::Jpg, self::Jpeg, self::Png,
            self::Webp, self::Gif, self::Svg,
            self::Jfif, self::Bmp, self::Tiff,
            self::Tif, self::Heic, self::Ico                   => 'Imagen',
            self::Mp4, self::Avi, self::Mov,
            self::Mkv, self::Webm                   => 'Video',
            self::Mp3, self::Wav, self::Ogg,
            self::Flac, self::Aac                   => 'Audio',
            self::Zip, self::Rar, self::SevenZ,
            self::Tar, self::Gz                     => 'Comprimido',
            self::Json                              => 'JSON',
            self::Xml                               => 'XML',
            self::Html, self::Htm                   => 'HTML',
            self::Unknown                           => 'Desconocido',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Pdf                              => 'fa-file-pdf',
            self::Doc, self::Docx                  => 'fa-file-word',
            self::Xls, self::Xlsx, self::Csv       => 'fa-file-excel',
            self::Ppt, self::Pptx                  => 'fa-file-powerpoint',
            self::Txt, self::Rtf                   => 'fa-file-lines',
            self::Odt                              => 'fa-file-word',
            self::Ods                              => 'fa-file-excel',
            self::Odp                              => 'fa-file-powerpoint',
            self::Odg, self::Odf                   => 'fa-file',
            self::Jpg, self::Jpeg, self::Png,
            self::Webp, self::Gif, self::Svg,
            self::Jfif, self::Bmp, self::Tiff,
            self::Tif, self::Heic, self::Ico       => 'fa-file-image',
            self::Mp4, self::Avi, self::Mov,
            self::Mkv, self::Webm                  => 'fa-file-video',
            self::Mp3, self::Wav, self::Ogg,
            self::Flac, self::Aac                  => 'fa-file-audio',
            self::Zip, self::Rar, self::SevenZ,
            self::Tar, self::Gz                    => 'fa-file-zipper',
            self::Json, self::Xml,
            self::Html, self::Htm                  => 'fa-file-code',
            self::Cer, self::Key, self::Req        => 'fa-file-shield',
            self::Dec, self::Diot, self::Xslt,
            self::Sua, self::Dis, self::Idse,
            self::Noi, self::Sin, self::Sar,
            self::Pol, self::Coi, self::Bdf,
            self::Sae, self::Nomixml               => 'fa-file-invoice',
            self::Unknown                          => 'fa-file',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pdf                              => '#e74c3c',
            self::Doc, self::Docx                  => '#2980b9',
            self::Xls, self::Xlsx, self::Csv       => '#27ae60',
            self::Ppt, self::Pptx                  => '#e67e22',
            self::Txt, self::Rtf                   => '#67b3d3',
            self::Odt                              => '#3498db',
            self::Ods                              => '#2ecc71',
            self::Odp                              => '#e67e22',
            self::Odg, self::Odf                   => '#1abc9c',
            self::Jpg, self::Jpeg, self::Png,
            self::Webp, self::Gif, self::Svg,
            self::Jfif, self::Bmp, self::Tiff,
            self::Tif, self::Heic, self::Ico       => '#8e44ad',
            self::Mp4, self::Avi, self::Mov,
            self::Mkv, self::Webm                  => '#c0392b',
            self::Mp3, self::Wav, self::Ogg,
            self::Flac, self::Aac                  => '#16a085',
            self::Zip, self::Rar, self::SevenZ,
            self::Tar, self::Gz                    => '#f39c12',
            self::Json, self::Xml,
            self::Html, self::Htm                  => '#95a5a6',
            self::Cer, self::Key, self::Req        => '#c0392b',  // Rojo SAT
            self::Dec, self::Diot, self::Xslt      => '#e74c3c',  // Rojo SAT claro
            self::Sua, self::Dis, self::Idse,
            self::Noi                              => '#2471a3',  // Azul IMSS
            self::Sin                              => '#1a5276',  // Azul INFONAVIT
            self::Sar                              => '#117a65',  // Verde SAR
            self::Pol, self::Coi, self::Bdf,
            self::Sae, self::Nomixml               => '#6c3483',  // Morado ERP
            self::Unknown                          => '#7f8c8d',
        };
    }

    public function mimes(): array
    {
        $customMimes =  config('file-extensions.customMimes');

        if (isset($customMimes[$this->value])) {
            return $customMimes[$this->value];
        }

        return MimeTypes::getDefault()->getMimeTypes($this->value);
    }

    public static function fromExtension(string $extension): self
    {
        $extension = strtolower($extension);

        return self::tryFrom($extension) ?? self::Unknown;
    }

    public static function fromMime(string $mimeType): self
    {
        $extensions = MimeTypes::getDefault()->getExtensions($mimeType);

        if (empty($extensions)) {
            return self::Unknown;
        }

        return self::fromExtension($extensions[0]);
    }

    public static function toSelectOptions(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->value] = $case->label();
        }
        return $array;
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
