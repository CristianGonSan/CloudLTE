<?php

namespace App\Http\Controllers;

use App\Models\UserFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MediaController extends Controller
{
    public function show(int $mediaId): BinaryFileResponse
    {
        return $this->inline($this->findMedia($mediaId));
    }

    public function download(int $mediaId): BinaryFileResponse
    {
        return $this->attachment($this->findMedia($mediaId));
    }

    private function findMedia(int $mediaId): Media
    {
        $media = Media::findOrFail($mediaId);

        abort_unless(file_exists($media->getPath()), 404);

        return $media;
    }

    private function inline(Media $media): BinaryFileResponse
    {
        return response()->file($media->getPath(), [
            'Content-Type'        => $media->mime_type,
            'Content-Disposition' => 'inline; filename="' . $media->file_name . '"',
            'Cache-Control'       => 'private, max-age=3600',
        ]);
    }

    private function attachment(Media $media): BinaryFileResponse
    {
        return response()->download(
            $media->getPath(),
            $media->file_name,
            ['Content-Type' => $media->mime_type]
        );
    }
}
