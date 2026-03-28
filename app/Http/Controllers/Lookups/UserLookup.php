<?php

namespace App\Http\Controllers\Lookups;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserLookup extends Controller
{
    public function select2(Request $request): JsonResponse
    {
        $query = User::query();

        if ($request->has('active')) {
            $request->boolean('active') ? $query->active() : $query->inactive();
        }

        if ($request->has('term')) {
            $term = $request->string('term');
            $query->where(
                fn($q) => $q
                    ->where('name', 'like', "%$term%")
                    ->orWhere('email', 'like', "$term%")
            );
        }

        $query->orderBy('name');

        $results = $query->paginate(16, ['id', 'name', 'email']);

        $map = $results->map(fn(User $item) => [
            'id'          => $item->id,
            'text'        => $item->name,
            'description' => $item->email,
        ]);

        return response()->json([
            'results'    => $map,
            'pagination' => ['more' => $results->hasMorePages()],
        ]);
    }
}
