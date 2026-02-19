<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PracticeCS Programming Interface
    |--------------------------------------------------------------------------
    |
    | Configuration for connecting to the PracticeCS API service.
    |
    */

    // Master switch - must be explicitly enabled
    'enabled' => env('PRACTICECS_API_ENABLED', false),

    // Base URL of the PracticeCS API microservice
    'base_url' => env('PRACTICECS_API_BASE_URL', 'http://localhost:8001'),

    // API key for authentication
    'api_key' => env('PRACTICECS_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */

    'rate_limit' => [
        'enabled' => env('PRACTICECS_RATE_LIMIT_ENABLED', true),
        'max_requests' => env('PRACTICECS_RATE_LIMIT_MAX', 1000),
        'per_seconds' => env('PRACTICECS_RATE_LIMIT_PERIOD', 3600),
    ],

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    */

    'retry' => [
        'max_attempts' => env('PRACTICECS_RETRY_MAX', 3),
        'base_delay_ms' => env('PRACTICECS_RETRY_DELAY', 100),
        'multiplier' => env('PRACTICECS_RETRY_MULTIPLIER', 2),
    ],

    /*
    |--------------------------------------------------------------------------
    | Timeouts
    |--------------------------------------------------------------------------
    */

    'timeout' => [
        'connect' => env('PRACTICECS_TIMEOUT_CONNECT', 5),
        'request' => env('PRACTICECS_TIMEOUT_REQUEST', 30),
    ],

];
