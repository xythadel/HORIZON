<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SkillTest;
use OpenAI\Laravel\Facades\OpenAI;
use App\Http\Controllers\Controller;

class SkillTestController extends Controller
{
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

        $normalize = fn($text) => strtolower(trim(preg_replace('/\s+/', ' ', $text)));
        $normalizedOutput = $normalize($output);
        $normalizedExpected = $normalize($skillTest->expected_output);
        $isCorrect = ($error === '' && $normalizedOutput === $normalizedExpected);

        $aiFeedback = null;

        $prompt = $isCorrect
            ? "
            The following student's PHP code runs correctly and produces the expected output.
            Please analyze it and provide a short, constructive suggestion for improvement or a better approach.
            Focus on coding style, readability, or optimization.

            Code:
            $code

            Output:
            $output

            Keep your feedback under 2 sentences.
            "
            : "
            Analyze this student's PHP code and help them fix it.

            Code:
            $code

            Input:
            $input

            Output:
            $output

            Expected Output:
            {$skillTest->expected_output}

            Provide a short, clear explanation of what went wrong and one suggestion to fix it.
            Keep it under 2 sentences.
            ";

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful PHP programming tutor.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            $aiFeedback = trim($response->choices[0]->message->content ?? '');
        } catch (\Exception $e) {
            $aiFeedback = "AI suggestion unavailable: " . $e->getMessage();
        }

        return response()->json([
            'output' => trim($output),
            'error' => trim($error),
            'correct' => $isCorrect,
            'expected' => $skillTest->expected_output,
            'feedback' => $aiFeedback,
        ]);
    }
}