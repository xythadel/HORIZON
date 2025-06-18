<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CompilerController extends Controller
{
    public function runCode(Request $request)
    {
        $clientId = 'c07a615c3a04ba1ea41b468942a70130';
        $clientSecret = '6dbcecb64062c7b1707f2300f176b5d10e59bbc5db0ac24a741d5ba5b02e68b3';

        $response = Http::post('https://api.jdoodle.com/v1/execute', [
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'script' => $request->input('code'),
            'language' => $request->input('language'),
            'versionIndex' => '0'
        ]);

        $responseData = $response->json();

        if (isset($responseData['output'])) {
            return response()->json(['output' => $responseData['output']]);
        } else {
            return response()->json([
                'output' => 'Error: ' . ($responseData['error'] ?? 'Unknown error'),
                'raw_response' => $responseData
            ]);
        }
    }

}
