<?php

namespace App\Traits\Models;

use Illuminate\Support\Str;

trait TruncateText
{
    protected const SIZES = [
        'tiny'   => 8,
        'short'  => 32,
        'medium' => 64,
        'long'   => 128,
    ];

    protected const DEFAULT_TEXT = 'S/N';

    /**
     * Devuelve el texto limitado según el tamaño indicado.
     */
    public function truncateText(string $attribute, string $size, string $default = self::DEFAULT_TEXT): string
    {
        $limit = self::SIZES[$size] ?? self::SIZES['medium'];
        $value = $this->getAttribute($attribute);

        if (blank($value)) {
            return $default;
        }

        return Str::limit((string) $value, $limit);
    }

    // Métodos de acceso rápido
    public function tinyText(string $attribute, string $default = self::DEFAULT_TEXT): string
    {
        return $this->truncateText($attribute, 'tiny', $default);
    }

    public function shortText(string $attribute, string $default = self::DEFAULT_TEXT): string
    {
        return $this->truncateText($attribute, 'short');
    }

    public function mediumText(string $attribute, string $default = self::DEFAULT_TEXT): string
    {
        return $this->truncateText($attribute, 'medium', $default);
    }

    public function longText(string $attribute, string $default = self::DEFAULT_TEXT): string
    {
        return $this->truncateText($attribute, 'long', $default);
    }
}
