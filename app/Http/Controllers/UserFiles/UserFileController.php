<?php

namespace App\Http\Controllers\UserFiles;

use App\Http\Controllers\Controller;
use App\Models\UserFile;
use Illuminate\View\View;

class UserFileController extends Controller
{
    public function index(): View
    {
        return view('user-files.index');
    }

    public function show(int $id): View
    {
        abort_if(!UserFile::where('id', $id)->exists(), 404);

        return view('user-files.show', [
            'userFileId' => $id
        ]);
    }

    public function create(): View
    {
        return view('user-files.create');
    }

    public function edit(int $id): View
    {
        abort_if(!UserFile::where('id', $id)->exists(), 404);

        return view('user-files.edit', [
            'userFileId' => $id
        ]);
    }
}
