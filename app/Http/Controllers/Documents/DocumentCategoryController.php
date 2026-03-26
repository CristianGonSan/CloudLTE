<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentCategoryController extends Controller
{
    public function index(): View
    {
        return view('documents.categories.index');
    }

    public function show(int $id): View
    {
        abort_if(!DocumentCategory::where('id', $id)->exists(), 404);

        return view('documents.categories.show', [
            'categoryId' => $id
        ]);
    }

    public function create(): View
    {
        return view('documents.categories.create');
    }

    public function edit(int $id): View
    {
        abort_if(!DocumentCategory::where('id', $id)->exists(), 404);

        return view('documents.categories.edit', [
            'categoryId' => $id
        ]);
    }
}
