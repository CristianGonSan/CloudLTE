<?php

namespace App\Http\Controllers\Lookups;

use App\Http\Controllers\Controller;
use App\Models\DocumentCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentCategoryLookup extends Controller
{
    public function select2(Request $request): JsonResponse
    {
        $query = DocumentCategory::query();

        if ($request->has('active')) {
            $request->boolean('active') ? $query->active() : $query->inactive();
        }

        if ($request->has('term')) {
            $term = $request->string('term');
            $query->where('name', 'like', "%$term%");
        }

        $query->orderBy('name');

        $results = $query->paginate(16, ['id', 'name']);

        $map = $results->map(fn(DocumentCategory $item) => [
            'id'          => $item->id,
            'text'        => $item->name,
        ]);

        return response()->json([
            'results'    => $map,
            'pagination' => ['more' => $results->hasMorePages()],
        ]);
    }
}
