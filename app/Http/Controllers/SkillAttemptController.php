<?php

namespace App\Http\Controllers;

use App\Models\SkillAttempts;
use App\Models\SkillTest;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillAttemptController extends Controller
{
     // User submits code attempt
    public function store(Request $request, $testId)
    {
        $request->validate([
            'submitted_code' => 'required|string',
            'language' => 'required|in:php,javascript',
        ]);

        $test = SkillTest::findOrFail($testId);

        // Prepare input/output
        $input = $test->test_input;
        $expectedOutput = trim($test->expected_output);
        $submittedCode = $request->submitted_code;
        $language = $request->language;

        // Run the code safely (here simplified with shell_exec)
        $actualOutput = $this->executeCode($submittedCode, $input, $language);

        // Compare results
        $passed = trim($actualOutput) === $expectedOutput;

        // Save attempt
        $attempt = SkillAttempts::create([
            'user_id' => Auth::id(),
            'skill_test_id' => $test->id,
            'submitted_code' => $submittedCode,
            'language' => $language,
            'output' => $actualOutput,
            'passed' => $passed,
        ]);

        return response()->json([
            'message' => 'Attempt recorded',
            'passed' => $passed,
            'expected' => $expectedOutput,
            'actual' => $actualOutput,
        ]);
    }

    // Show logged-in user’s attempts
    public function myAttempts()
    {
        $attempts = SkillAttempts::with('skillTest')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json($attempts);
    }

    // Execute user code (⚠️ simple sandbox — needs security in production)
    private function executeCode($code, $input, $language)
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'code_');

        if ($language === 'php') {
            $file = $tempFile . ".php";
            file_put_contents($file, $code);
            $cmd = "echo " . escapeshellarg($input) . " | php " . escapeshellarg($file) . " 2>&1";
        } elseif ($language === 'javascript') {
            $file = $tempFile . ".js";
            file_put_contents($file, $code);
            $cmd = "echo " . escapeshellarg($input) . " | node " . escapeshellarg($file) . " 2>&1";
        } else {
            return "Unsupported language";
        }

        $output = shell_exec($cmd);
        return trim($output);
    }
}
