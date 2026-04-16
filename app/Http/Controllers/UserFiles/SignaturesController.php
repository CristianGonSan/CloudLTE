<?php

namespace App\Http\Controllers\UserFiles;

use App\Http\Controllers\Controller;
use App\Models\SignatureRequest;
use App\Models\UserFile;
use Illuminate\View\View;

class SignaturesController extends Controller
{
    public function index(): View
    {
        return view('user-files.signatures.index');
    }

    public function show(int $userFileId, int $signatureRequestId): View
    {
        abort_if(!SignatureRequest::where('id', $signatureRequestId)->exists(), 404);

        return view('user-files.signatures.show', [
            'userFileId'            => $userFileId,
            'signatureRequestId'    => $signatureRequestId
        ]);
    }

    public function create(int $id): View
    {
        abort_if(!UserFile::where('id', $id)->exists(), 404);

        return view('user-files.signatures.create', [
            'userFileId' => $id
        ]);
    }

    public function edit(int $userFileId, int $signatureRequestId): View
    {
        abort_if(!SignatureRequest::where('id', $signatureRequestId)->exists(), 404);

        return view('user-files.signatures.edit', [
            'userFileId'            => $userFileId,
            'signatureRequestId'    => $signatureRequestId
        ]);
    }

    public function sign(int $userFileId, int $signatureRequestId, int $signatoryId): View
    {
        abort_if(!SignatureRequest::where('id', $signatureRequestId)->exists(), 404);

        return view('user-files.signatures.sign', [
            'userFileId'            => $userFileId,
            'signatureRequestId'    => $signatureRequestId,
            'signatoryId'           => $signatoryId
        ]);
    }
}
