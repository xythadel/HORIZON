<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;

class VueTopicController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:1000',
    ]);

    Topic::create([
        'name' => $request->name,
        'course_id' => 1, // Vue
    ]);

    return redirect()->route('admin.vue.topics')->with('success', 'Vue topic created!');
}

}
