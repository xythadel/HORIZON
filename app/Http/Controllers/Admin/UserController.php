<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all(); // or paginate if needed
        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }
}
