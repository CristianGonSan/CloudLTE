<?php

namespace App\Models;

use App\Enums\Document\SignatoryStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $document_id
 * @property int $user_id
 * @property SignatoryStatus $status
 * @property \Illuminate\Support\Carbon|null $updated_status_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Document $document
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSignatory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSignatory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSignatory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSignatory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSignatory whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSignatory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSignatory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSignatory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSignatory whereUpdatedStatusAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentSignatory whereUserId($value)
 * @mixin \Eloquent
 */
class DocumentSignatory extends Model
{
    protected $fillable = [
        'document_id',
        'user_id',
        'status',
        'updated_status_at',
    ];

    protected $casts = [
        'status'            => SignatoryStatus::class,
        'updated_status_at' => 'datetime',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
