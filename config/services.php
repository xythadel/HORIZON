<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],


    'jdoodle' => [
        'client_id' => env('JDOODLE_CLIENT_ID'),
        'client_secret' => env('JDOODLE_CLIENT_SECRET'),
    ],

    
    'google' => [
    'client_id' => env('199783400018-5figl7r3de1cffrvu3een7glec78rb29.apps.googleusercontent.com'),
    'client_secret' => env('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'),
    'redirect' => env('http://127.0.0.1:8000/auth/google/callback'),
    ],

];
