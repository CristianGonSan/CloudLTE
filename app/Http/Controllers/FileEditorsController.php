<?php

namespace App\Http\Controllers;

use App\Enums\FileExtensionSupport;
use App\Models\UserFile;
use Illuminate\Http\Request;

class FileEditorsController extends Controller
{
    public function pdf(int $userFileId)
    {
        $userFile   = UserFile::findOrFail($userFileId);
        $media      = $userFile->getFile();
        $extension  = FileExtensionSupport::fromMedia($media);

        abort_unless($extension->editor() === 'pdf', 404);

        return view('files.editor-pdf', [
            'userFile'  => $userFile,
            'media'     => $media,
            'extension' => $extension
        ]);
    }
}
