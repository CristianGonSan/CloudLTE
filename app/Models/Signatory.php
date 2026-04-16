<?php

namespace App\Models;

use App\Enums\SignatoryStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $request_id
 * @property int $user_id
 * @property SignatoryStatus $status
 * @property \Illuminate\Support\Carbon|null $status_changed_at
 * @property int $required_signatures
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SignatureRequest $signatureRequest
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Signatory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Signatory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Signatory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Signatory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Signatory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Signatory whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Signatory whereRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Signatory whereRequiredSignatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Signatory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Signatory whereStatusChangedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Signatory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Signatory whereUserId($value)
 * @mixin \Eloquent
 */
class Signatory extends Model
{
    protected $fillable = [
        'request_id',
        'user_id',
        'required_signatures',
        'status',
        'status_changed_at',
        'reason',
    ];

    protected $casts = [
        'status'            => SignatoryStatus::class,
        'status_changed_at' => 'datetime',
    ];

    // ─── Relations ────────────────────────────────────────────────────────────

    public function signatureRequest(): BelongsTo
    {
        return $this->belongsTo(SignatureRequest::class, 'request_id');
    }

    public function isPending(): bool
    {
        return $this->status === SignatoryStatus::Pending;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function markAs(SignatoryStatus $status, ?string $reason = null): void
    {
        $this->update([
            'status'            => $status,
            'status_changed_at' => now(),
            'reason'            => $reason,
        ]);
    }
}
