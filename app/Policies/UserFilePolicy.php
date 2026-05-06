<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserFile;
use Illuminate\Auth\Access\Response;

class UserFilePolicy
{
    public function editFile(User $user, UserFile $userFile): bool
    {
        return
            $userFile->isOwnedBy($user) &&
            $userFile->getLastSignatureRequest() === null &&
            $userFile->getFileExtensionSupport()->isEditorSupported();
    }

    public function delete(User $user, UserFile $userFile): bool
    {
        return
            $userFile->isOwnedBy($user) &&
            $userFile->getLastSignatureRequest() === null;
    }

    public function createSignatureRequest(User $user, UserFile $userFile): bool
    {
        $lastRequest = $userFile->getLastSignatureRequest();
        return
            $userFile->isOwnedBy($user) &&
            (
                $lastRequest === null ||
                !$lastRequest->isPending()
            ) &&
            $userFile->getFileExtensionSupport()->isSignatureRequestSupported();
    }
}
