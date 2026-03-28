<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    public function show(Request $request, int $mediaId): BinaryFileResponse
    {
        $media = $this->findAuthorizedMedia($request, $mediaId);

        $path = $media->getPath();

        abort_unless(file_exists($path), 404, 'Archivo no encontrado.');

        return response()->file($path, [
            'Content-Type'        => $media->mime_type,
            'Content-Disposition' => 'inline; filename="' . $media->file_name . '"',
            'Cache-Control'       => 'private, max-age=3600',
        ]);
    }

    public function download(Request $request, int $mediaId): BinaryFileResponse
    {
        $media = $this->findAuthorizedMedia($request, $mediaId);

        $path = $media->getPath();

        abort_unless(file_exists($path), 404, 'Archivo no encontrado.');

        return response()->download(
            $path,
            $media->file_name,
            ['Content-Type' => $media->mime_type]
        );
    }

    public function stream(Request $request, int $mediaId): StreamedResponse
    {
        $media = $this->findAuthorizedMedia($request, $mediaId);

        $path = $media->getPath();

        abort_unless(file_exists($path), 404, 'Archivo no encontrado.');

        $fileSize = filesize($path);
        $mimeType = $media->mime_type;

        // Soporte para Range Requests (reproductores de video/audio)
        $start  = 0;
        $end    = $fileSize - 1;
        $status = 200;
        $headers = [
            'Content-Type'   => $mimeType,
            'Accept-Ranges'  => 'bytes',
            'Cache-Control'  => 'private, max-age=3600',
        ];

        if ($request->hasHeader('Range')) {
            $status = 206;
            preg_match('/bytes=(\d+)-(\d*)/', $request->header('Range'), $matches);

            $start = (int) $matches[1];
            $end   = isset($matches[2]) && $matches[2] !== ''
                ? (int) $matches[2]
                : $fileSize - 1;

            $headers['Content-Range']  = "bytes {$start}-{$end}/{$fileSize}";
            $headers['Content-Length'] = $end - $start + 1;
        } else {
            $headers['Content-Length'] = $fileSize;
        }

        return response()->stream(function () use ($path, $start, $end) {
            $handle = fopen($path, 'rb');
            fseek($handle, $start);

            $chunkSize  = 1024 * 64; // 64 KB por chunk
            $remaining  = $end - $start + 1;

            while (!feof($handle) && $remaining > 0) {
                $toRead    = min($chunkSize, $remaining);
                $data      = fread($handle, $toRead);
                $remaining -= strlen($data);

                echo $data;
                flush();
            }

            fclose($handle);
        }, $status, $headers);
    }

    private function findAuthorizedMedia(Request $request, int $mediaId): Media
    {
        $media = Media::findOrFail($mediaId);

        return $media;
    }
}
