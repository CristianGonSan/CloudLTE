<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Contracts\View\View;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        return view('admin.roles.index');
    }

    public function show(int $id): View
    {
        abort_if(!Role::where('id', $id)->exists(), 404);

        return view('admin.roles.show', [
            'roleId' => $id
        ]);
    }

    public function create(): View
    {
        return view('admin.roles.create');
    }

    public function edit(int $id): View
    {
        abort_if(!Role::where('id', $id)->exists(), 404);

        return view('admin.roles.edit', [
            'roleId' => $id
        ]);
    }
}
