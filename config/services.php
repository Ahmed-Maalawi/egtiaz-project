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

    'firebase'  => [
        'phone_verification_key'    => env('FIREBASE_API_KEY'),
        'credentials'               => env('FIREBASE_CREDENTIALS'),
    ],

    'afs_payment_gateway' => [
        'url' => env('AFS_PAYMENT_GATEWAY_URL', 'https://eu-test.oppwa.com/paybylink/v1'),
        'entity_id' => env('AFS_PAYMENT_GATEWAY_ENTITY_ID'),
        'auth_token' => env('AFS_PAYMENT_GATEWAY_AUTH_TOKEN'),
        'shopper_result_url' => env('AFS_PAYMENT_GATEWAY_SHOPPER_RESULT_URL'),
    ],
];
