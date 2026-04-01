<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile whereUserId($value)
 * @mixin \Eloquent
 */
class UserFile extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFile(): ?Media
    {
        return $this->getFirstMedia('file');
    }

    public function hardDelete(): void
    {
        $this->deleteAllMedia();
        $this->delete();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('file')
            ->singleFile();
    }
}
