<?php

namespace App\Models;

use App\Enums\Document\ActivityType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $document_id
 * @property int|null $user_id
 * @property ActivityType $type
 * @property array<array-key, mixed>|null $details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Document $document
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentActivity whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentActivity whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentActivity whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentActivity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentActivity whereUserId($value)
 * @mixin \Eloquent
 */
class DocumentActivity extends Model
{
    protected $fillable = [
        'document_id',
        'user_id',
        'type',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'type'    => ActivityType::class,
            'details' => 'array',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
