<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Config
    |--------------------------------------------------------------------------
    |
    | The following credentials are required for the API connection.
    |
    */
    'api'    => [
        'domain' => env('SSOFY_API_DOMAIN', 'us-1.api.ssofy.com'),
        'key'    => env('SSOFY_API_KEY'),
        'secret' => env('SSOFY_API_SECRET'),
        'secure' => env('SSOFY_API_HTTPS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    |
    | Caching can speed up response time by reducing the number of connections
    | to SSOfy. Once a token is deleted, an event will be sent to this
    | application which invalidates the deleted token.
    |
    */
    'cache'  => [
        'store' => env('SSOFY_CACHE_DRIVER', null),
        'ttl'   => env('SSOFY_CACHE_TTL', 3600), // time-to-live in seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | SSOfy OAuth2 Client Settings
    |--------------------------------------------------------------------------
    */
    'oauth2' => [
        'url'               => env('SSOFY_URL'),
        'client_id'         => env('SSOFY_CLIENT_ID'),
        'client_secret'     => env('SSOFY_CLIENT_SECRET'),
        'redirect_uri'      => '/sso/callback',
        'scopes'            => ['*'],
        'locale'            => null, // 'en',
        'timeout'           => 3600, // wait-for-login timeout in seconds
        'pkce_method'       => 'S256', // options: plain, S256
        'pkce_verification' => true,
        'state'             => [
            'store' => env('SSOFY_STATE_CACHE_DRIVER', 'file'),
            'ttl'   => env('SSOFY_STATE_CACHE_TTL', 31536000), // time-to-live in seconds (default: 1-year)
        ],
    ],
];
