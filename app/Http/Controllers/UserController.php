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
// ...existing code...
public function admins()
{
    return response()->json(User::where('role', 'admin')->select('id', 'name', 'email', 'role', 'created_at')->get());
}

public function regularUsers()
{
    return response()->json(User::where('role', 'user')->select('id', 'name', 'email', 'role', 'created_at')->get());
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users',
        'role' => 'required|in:admin,user',
        'password' => 'required|string|min:6',
    ]);
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'password' => bcrypt($request->password),
    ]);
    return response()->json($user, 201);
}

public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();
    return response()->json(['message' => 'User deleted']);
}
// ...existing code...
}
