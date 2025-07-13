<?php

namespace App\Http\Controllers;

use App\Models\UserDifficulty;
use Illuminate\Http\Request;

class UserDifficultyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_name' => 'required|string',
            'difficulty_level' => 'required|integer|min:1|max:3',
            'score' => 'required|numeric',
        ]);

        $difficulty = $request->difficulty_level;

        $userDifficulty = UserDifficulty::updateOrCreate(
            ['user_id' => $request->user_id, 'course_name' => $request->course_name],
            ['difficulty_level' => $difficulty, 'score' => $request->score]
        );

        return response()->json([
            'message' => 'Difficulty level saved successfully.',
            'difficulty_level' => $difficulty,
            'score' => $request->score,
        ]);
    }


    public function show($user_id, $course_name)
    {
        $userDifficulty = UserDifficulty::where('user_id', $user_id)
            ->where('course_name', $course_name)
            ->first();

        return response()->json($userDifficulty);
    }

    public function index($user_id)
    {
        $difficulties = UserDifficulty::where('user_id', $user_id)->get();

        return response()->json($difficulties);
    }
}
