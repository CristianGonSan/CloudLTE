<?php

namespace App\Http\Controllers\UserFiles;

use App\Enums\FileExtensionSupport;
use App\Http\Controllers\Controller;
use App\Models\UserFile;
use Illuminate\Http\Request;

class FileEditorController extends Controller
{
    public function pdf(int $userFileId)
    {
        $userFile   = UserFile::findOrFail($userFileId);

        $this->authorize('editFile', $userFile);

        $media      = $userFile->getFile();
        $extension  = FileExtensionSupport::fromMedia($media);

        abort_unless($extension->editor() === 'pdf', 404);

        return view('user-files.editor.editor-pdf', [
            'userFile'  => $userFile,
            'media'     => $media,
            'extension' => $extension
        ]);
    }
}
