<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    //
     public function index()
    {
        return inertia('Admin/AdminDashboard'); // Or whatever Inertia page you want to show
    }
    public function getAllUsers()
{
    return response()->json(User::all());
}
}
