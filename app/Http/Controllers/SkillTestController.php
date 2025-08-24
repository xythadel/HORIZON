<?php

namespace App\Http\Controllers;

use App\Models\SkillTest;
use Illuminate\Http\Request;

class SkillTestController extends Controller
{
    public function index()
    {
        return response()->json(SkillTest::all());
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'language' => 'required|in:php,javascript',
            'test_input' => 'nullable|string',
            'expected_output' => 'required|string',
        ]);

        $test = SkillTest::create($request->all());

        return response()->json([
            'message' => 'Skill test created successfully',
            'data' => $test
        ], 201);
    }
    public function show(SkillTest $skillTest)
    {
        return response()->json($skillTest);
    }
    public function update(Request $request, SkillTest $skillTest)
    {
        $request->validate([
            'title' => 'sometimes|string',
            'description' => 'nullable|string',
            'language' => 'sometimes|in:php,javascript',
            'test_input' => 'nullable|string',
            'expected_output' => 'sometimes|string',
        ]);

        $skillTest->update($request->all());

        return response()->json([
            'message' => 'Skill test updated',
            'data' => $skillTest
        ]);
    }
    public function destroy(SkillTest $skillTest)
    {
        $skillTest->delete();

        return response()->json(['message' => 'Skill test deleted']);
    }
    public function byTopic($topicId)
    {
        $test = SkillTest::where('topic_id', $topicId)->first();

        if (!$test) {
            return response()->json(['message' => 'No skill test available for this topic'], 404);
        }

        return response()->json($test);
    }
    public function run(Request $request)
    {
        $skillTest = SkillTest::findOrFail($request->input('skill_test_id'));
        $code = $request->input('code');
        $input = $skillTest->test_input;

        $file = tempnam(sys_get_temp_dir(), 'code') . '.php';
        file_put_contents($file, $code);

        $command = "php $file";
        $process = proc_open($command, [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"],
        ], $pipes);

        fwrite($pipes[0], $input);
        fclose($pipes[0]);

        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $error = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        proc_close($process);

        unlink($file);

        $output = trim($output);
        $error = trim($error);

        $isCorrect = ($error === '' && $output === trim($skillTest->expected_output));

        return response()->json([
            'output' => $output,
            'error' => $error,
            'correct' => $isCorrect,
            'expected' => $skillTest->expected_output,
        ]);
    }

}
