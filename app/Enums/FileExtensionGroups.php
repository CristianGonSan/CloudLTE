<?php

namespace App\Enums;

use Symfony\Component\Mime\MimeTypes;


enum FileExtensionGroups: string
{
    // Documentos comunes
    case Pdf            = 'pdf';
    case Word           = 'word';
    case Excel          = 'excel';
    case PowerPoint     = 'powerpoint';
    case Text           = 'text';

    // OpenDocument
    case OpenDocument   = 'opendocument';

    // Multimedia
    case Image          = 'image';
    case Video          = 'video';
    case Audio          = 'audio';

    // Archivos comprimidos
    case Compressed     = 'compressed';

       // Código / Datos
    case Code               = 'code';
    case SatFirma           = 'sat_firma';
    case SatDeclaraciones   = 'sat_declaraciones';
    case Imss               = 'imss';
    case InfonavitSar       = 'infonavit_sar';
    case Erp                = 'erp';

    public function label(): string
    {
        return match ($this) {
            self::Pdf               => 'PDF',
            self::Word              => 'Word',
            self::Excel             => 'Excel',
            self::PowerPoint        => 'PowerPoint',
            self::Text              => 'Texto plano',
            self::OpenDocument      => 'OpenDocument',
            self::Image             => 'Imagen',
            self::Video             => 'Video',
            self::Audio             => 'Audio',
            self::Compressed        => 'Comprimido',
            self::Code              => 'Código / Datos',
            self::SatFirma          => 'SAT — e.firma y CSD',
            self::SatDeclaraciones  => 'SAT — Declaraciones y Contabilidad',
            self::Imss              => 'IMSS / SUA',
            self::InfonavitSar      => 'INFONAVIT / SAR',
            self::Erp               => 'Contabilidad / ERP',
        };
    }

    public function extensions(): array
    {
        return match ($this) {
            self::Pdf => [
                FileExtensionSupport::Pdf,
            ],

            self::Word => [
                FileExtensionSupport::Doc,
                FileExtensionSupport::Docx,
                FileExtensionSupport::Rtf,
            ],

            self::Excel => [
                FileExtensionSupport::Xls,
                FileExtensionSupport::Xlsx,
                FileExtensionSupport::Csv,
            ],

            self::PowerPoint => [
                FileExtensionSupport::Ppt,
                FileExtensionSupport::Pptx,
            ],

            self::Text => [
                FileExtensionSupport::Txt,
            ],

            self::OpenDocument => [
                FileExtensionSupport::Odt,
                FileExtensionSupport::Ods,
                FileExtensionSupport::Odp,
                FileExtensionSupport::Odg,
                FileExtensionSupport::Odf,
            ],

            self::Image => [
                FileExtensionSupport::Jpg,
                FileExtensionSupport::Jpeg,
                FileExtensionSupport::Png,
                FileExtensionSupport::Webp,
                FileExtensionSupport::Gif,
                FileExtensionSupport::Svg,
                FileExtensionSupport::Jfif,
                FileExtensionSupport::Bmp,
                FileExtensionSupport::Tiff,
                FileExtensionSupport::Tif,
                FileExtensionSupport::Heic,
            ],

            self::Video => [
                FileExtensionSupport::Mp4,
                FileExtensionSupport::Avi,
                FileExtensionSupport::Mov,
                FileExtensionSupport::Mkv,
                FileExtensionSupport::Webm,
            ],

            self::Audio => [
                FileExtensionSupport::Mp3,
                FileExtensionSupport::Wav,
                FileExtensionSupport::Ogg,
                FileExtensionSupport::Flac,
                FileExtensionSupport::Aac,
            ],

            self::Compressed => [
                FileExtensionSupport::Zip,
                FileExtensionSupport::Rar,
                FileExtensionSupport::SevenZ,
                FileExtensionSupport::Tar,
                FileExtensionSupport::Gz,
            ],

            self::Code => [
                FileExtensionSupport::Json,
                FileExtensionSupport::Xml,
                FileExtensionSupport::Html,
                FileExtensionSupport::Htm,
            ],

            self::SatFirma => [
                FileExtensionSupport::Cer,
                FileExtensionSupport::Key,
                FileExtensionSupport::Req,
            ],

            self::SatDeclaraciones => [
                FileExtensionSupport::Dec,
                FileExtensionSupport::Diot,
                FileExtensionSupport::Xslt,
                FileExtensionSupport::Nomixml,
            ],

            self::Imss => [
                FileExtensionSupport::Sua,
                FileExtensionSupport::Dis,
                FileExtensionSupport::Idse,
                FileExtensionSupport::Noi,
            ],

            self::InfonavitSar => [
                FileExtensionSupport::Sin,
                FileExtensionSupport::Sar,
            ],

            self::Erp => [
                FileExtensionSupport::Pol,
                FileExtensionSupport::Coi,
                FileExtensionSupport::Bdf,
                FileExtensionSupport::Sae,
            ],
        };
    }

    public function mimes(): array
    {
        return collect($this->extensions())
            ->flatMap(fn(FileExtensionSupport $ext) => $ext->mimes())
            ->unique()
            ->values()
            ->all();
    }

    public static function toSelectOptions(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->value] = $case->label();
        }
        return $array;
    }

    public static function toGroupedSelectOptions(): array
    {
        $groups = [];

        foreach (self::cases() as $group) {
            foreach ($group->extensions() as $ext) {
                $groups[$group->label()][$ext->value] = $ext->label();
            }
        }

        return $groups;
    }
}
