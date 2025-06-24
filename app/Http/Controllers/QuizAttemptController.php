<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizAttempt;

class QuizAttemptController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'topic_id' => 'required|exists:topics,id',
            'score' => 'required|integer',
            'total' => 'required|integer',
            'passed' => 'required|boolean',
            'type' => 'required|in:pre,post',
            'timestamp' => 'required|date',
        ]);

        QuizAttempt::create([
            'user_id' => $validated['user_id'],
            'topic_id' => $validated['topic_id'],
            'score' => $validated['score'],
            'total' => $validated['total'],
            'passed' => $validated['passed'],
            'type' => $validated['type'],
            'created_at' => $validated['timestamp'],
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Attempt recorded successfully']);
    }

    public function getAttemptsByUser($userId)
    {
        return QuizAttempt::where('user_id', $userId)
            ->where('type', 'post')
            ->get(['topic_id', 'passed']);
    }

}
