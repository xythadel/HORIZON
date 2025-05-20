<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
     public function index()
    {
        return inertia('Admin/AdminDashboard'); // Or whatever Inertia page you want to show
    }
    
}
