<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;
use Inertia\Inertia;

class TopicController extends Controller
{
     public function index()
    {
        $topics = Topic::all();
        return Inertia::render('Admin/Topics/Index', [
            'topics' => $topics,
        ]);
    }
}
