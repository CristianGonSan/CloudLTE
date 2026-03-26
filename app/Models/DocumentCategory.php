<?php

namespace App\Models;

use App\Traits\Models\HasActiveState;
use App\Traits\Models\TruncateText;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Document> $documents
 * @property-read int|null $documents_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentCategory active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentCategory inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentCategory whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DocumentCategory extends Model
{
    use HasActiveState, TruncateText;

    protected $fillable = [
        'name',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    public function isInUse(): bool
    {
        return false;
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'category_id');
    }
}
