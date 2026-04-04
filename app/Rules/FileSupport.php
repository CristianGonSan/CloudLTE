<?php

namespace App\Rules;

use App\Enums\FileExtensionSupport;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileSupport implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /** @var UploadedFile $value */
        if (FileExtensionSupport::fromExtension($value->getClientOriginalExtension()) === FileExtensionSupport::Unknown) {
            $fail('Archivo no soportado');
        }
    }
}
