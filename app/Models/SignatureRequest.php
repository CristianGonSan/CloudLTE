<?php

namespace App\Models;

use App\Enums\SignatoryStatus;
use App\Enums\SignatureRequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property int $user_file_id
 * @property SignatureRequestStatus $status
 * @property \Illuminate\Support\Carbon|null $status_change_at
 * @property string|null $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Signatory> $signatories
 * @property-read int|null $signatories_count
 * @property-read \App\Models\UserFile $userFile
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureRequest whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureRequest whereStatusChangeAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SignatureRequest whereUserFileId($value)
 * @mixin \Eloquent
 */
class SignatureRequest extends Model
{
    protected $fillable = [
        'user_file_id',
        'status',
        'status_change_at',
        'message',
    ];

    protected $casts = [
        'status'           => SignatureRequestStatus::class,
        'status_change_at' => 'datetime',
    ];

    // ─── Relations ────────────────────────────────────────────────────────────

    public function isOwnedBy(User $user): bool
    {
        return $this->userFile->isOwnedBy($user);
    }

    public function isAllSignatoriesPending(): bool
    {
        foreach ($this->signatories as $signatory) {
            if ($signatory->status !== SignatoryStatus::Pending) {
                return false;
            }
        }

        return true;
    }

    public function canSign(User $user): bool
    {
        return $this->getSignatoryByUser($user)?->isPending();
    }

    public function isPending(): bool
    {
        return $this->status === SignatureRequestStatus::Pending;
    }

    public function userFile(): BelongsTo
    {
        return $this->belongsTo(UserFile::class, 'user_file_id');
    }

    public function signatories(): HasMany
    {
        return $this->hasMany(Signatory::class, 'request_id');
    }

    public function getSignatoryByUserId(int $userId): ?Signatory
    {
        return $this->signatories()->where('user_id', '=', $userId)->first();
    }

    public function getSignatoryByUser(User $user): ?Signatory
    {
        return $this->signatories()->where('user_id', '=', $user->id)->first();
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function markAs(SignatureRequestStatus $status): void
    {
        $this->update([
            'status'           => $status,
            'status_change_at' => now(),
        ]);
    }
}
