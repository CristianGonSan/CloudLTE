<?php

namespace App\Models;

use App\Enums\FileExtensionSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Str;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $version
 * @property \Illuminate\Support\Carbon|null $edited_at
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SignatureRequest> $signatureRequests
 * @property-read int|null $signature_requests_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile whereEditedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserFile whereVersion($value)
 * @mixin \Eloquent
 */
class UserFile extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'version',
        'edited_at',
        'description',
    ];

    protected $casts = [
        'edited_at' => 'datetime',
    ];

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFile(): ?Media
    {
        return $this->getFirstMedia('file');
    }

    public function getFileExtensionSupport(): FileExtensionSupport
    {
        return FileExtensionSupport::fromMedia($this->getFile());
    }

    public function getUrl(bool $download = false): string
    {
        $media = Media::where('model_type', '=', UserFile::class)
            ->where('model_id', '=', $this->id)->firstOrFail(['model_type', 'model_id', 'file_name']);

        $route = $download ? 'files.download' : 'files.content';
        $url = route($route, ['userFileId' => $this->id, 'fileName' => $media->file_name]);

        $url .= "?v={$this->version}";

        return $url;
    }

    public function getUrlRandom(bool $download = false): string
    {
        $ramdom = Str::random(6);
        return $this->getUrl($download) . "&r=$ramdom";
    }

    public function getLastSignatureRequest(): ?SignatureRequest
    {
        return $this->signatureRequests()->orderByDesc('created_at')->first();
    }

    public function signatureRequests(): HasMany
    {
        return $this->hasMany(SignatureRequest::class, 'user_file_id');
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
