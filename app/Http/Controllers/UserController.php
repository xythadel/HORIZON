<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::select('id', 'name', 'email', 'role', 'created_at')->get());
    }
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);
        $user = User::findOrFail(Auth::id());
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return back()->with('success', 'Account updated successfully.');
    }
}
