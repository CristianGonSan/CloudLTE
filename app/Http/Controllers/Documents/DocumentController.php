<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(): View
    {
        return view('documents.index');
    }

    public function show(int $id): View
    {
        abort_if(!Document::where('id', $id)->exists(), 404);

        return view('documents.show', [
            'documentId' => $id
        ]);
    }

    public function create(): View
    {
        return view('documents.create');
    }

    public function edit(int $id): View
    {
        abort_if(!Document::where('id', $id)->exists(), 404);

        return view('documents.edit', [
            'documentId' => $id
        ]);
    }
}
