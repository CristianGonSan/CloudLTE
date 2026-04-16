<?php

namespace App\Policies;

use App\Models\SignatureRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SignatureRequestPolicy
{
    public function edit(User $user, SignatureRequest $signatureRequest): bool
    {
        return
            $signatureRequest->isOwnedBy($user) &&
            $signatureRequest->isAllSignatoriesPending();
    }

    public function cancel(User $user, SignatureRequest $signatureRequest): bool
    {
        return
            $signatureRequest->isOwnedBy($user) &&
            $signatureRequest->isPending();
    }

    public function sign(User $user, SignatureRequest $signatureRequest): bool
    {
        return $signatureRequest->canSign($user);
    }
}
