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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'tokodaring' => [
        'client_id' => env('TOKODARING_CLIENTID'),
        'secret_key' => env('TOKODARING_SECRETKEY'),
        'report_url_dev' => env('TOKODARING_REPORT_URL_DEV'),
        'report_url_prod' => env('TOKODARING_REPORT_URL_PROD'),
        'confirm_url_dev' => env('TOKODARING_CONFIRM_URL_DEV'),
        'confirm_url_prod' => env('TOKODARING_CONFIRM_URL_PROD'),
        'send_to_lkpp'     => env('TOKODARING_SEND_TO_LKPP')
    ],
    'firebase' => [
        'server_key'   => env('FIREBASE_SERVER_KEY'),
        'url'       => env('FIREBASE_URL')
    ],
    'midtrans' => [
        'production' => [
            'server_key'    => env('PROD_SERVER_KEY'),
            'client_key'    => env('PROD_CLIENT_KEY'),
            'url'           => env('MIDTRANS_PROD_URL')
        ],
        'sandbox' => [
            'server_key'    => env('SANDBOX_SERVER_KEY'),
            'client_key'    => env('SANDBOX_CLIENT_KEY'),
            'url'           => env('MIDTRANS_SANDBOX_URL')
        ]
    ]

];
