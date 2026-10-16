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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'onlysms' => [
        'url' => 'https://onlysms.co.in/api/sms.aspx',
        'user_id' => env('ONLYSMS_USER_ID'),
        'password' => env('ONLYSMS_PASSWORD'),
        'sender_id' => env('ONLYSMS_SENDER_ID', 'DNDDEN'),
        'peid' => env('ONLYSMS_PEID'),
        'template_id' => env('ONLYSMS_TEMPLATE_ID', '1707172786516875968'),
        'clinic_phone' => env('ONLYSMS_CLINIC_PHONE', '9724667455'),
        'send_immediately' => env('ONLYSMS_SEND_IMMEDIATELY', false),
    ],

];
