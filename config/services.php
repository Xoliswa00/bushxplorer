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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'instagram' => [
        'account_id'   => env('INSTAGRAM_ACCOUNT_ID'),
        'access_token' => env('INSTAGRAM_ACCESS_TOKEN'),
    ],

    'facebook' => [
        'page_id'            => env('FACEBOOK_PAGE_ID'),
        'page_access_token'  => env('FACEBOOK_PAGE_ACCESS_TOKEN'),
    ],

    'monitoring' => [
        'enabled' => env('MONITORING_ENABLED', false),
        'url'     => env('MONITORING_URL'), // Base URL of the Xquisite monitoring instance for this site
        'token'   => env('MONITORING_TOKEN'),
    ],

];
