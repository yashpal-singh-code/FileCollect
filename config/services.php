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



    // 'stripe' => [
    //     'key' => env('STRIPE_KEY'),
    //     'secret' => env('STRIPE_SECRET'),

    //     'plans' => [
    //         'starter' => [
    //             'monthly' => env('STRIPE_STARTER_MONTHLY'),
    //             'yearly'  => env('STRIPE_STARTER_YEARLY'),
    //         ],
    //         'growth' => [
    //             'monthly' => env('STRIPE_GROWTH_MONTHLY'),
    //             'yearly'  => env('STRIPE_GROWTH_YEARLY'),
    //         ],
    //         'pro' => [
    //             'monthly' => env('STRIPE_PRO_MONTHLY'),
    //             'yearly'  => env('STRIPE_PRO_YEARLY'),
    //         ],
    //     ],
    // ],

    'razorpay' => [
        'key' => env('RAZORPAY_KEY'),
        'secret' => env('RAZORPAY_SECRET'),

        'plans' => [
            'starter' => [
                'monthly' => env('RAZORPAY_STARTER_MONTHLY'),
                'yearly'  => env('RAZORPAY_STARTER_YEARLY'),
            ],
            'growth' => [
                'monthly' => env('RAZORPAY_GROWTH_MONTHLY'),
                'yearly'  => env('RAZORPAY_GROWTH_YEARLY'),
            ],
            'pro' => [
                'monthly' => env('RAZORPAY_PRO_MONTHLY'),
                'yearly'  => env('RAZORPAY_PRO_YEARLY'),
            ],
        ],
    ],

];
