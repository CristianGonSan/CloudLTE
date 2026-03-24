<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index');
    }

    public function show(int $id): View
    {
        abort_if(!User::where('id', $id)->exists(), 404);

        return view('admin.users.show', [
            'userId' => $id
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function edit(int $id): View
    {
        abort_if(!User::where('id', $id)->exists(), 404);

        return view('admin.users.edit', [
            'userId' => $id
        ]);
    }
}
