<?php

/**
 * Sentry Error Tracking Configuration
 * 
 * This file handles the integration of Sentry for error tracking and monitoring
 * in production environments.
 * 
 * To set up:
 * 1. Create a Sentry account at https://sentry.io
 * 2. Create a new project for Laravel
 * 3. Copy your DSN
 * 4. Add to .env: SENTRY_LARAVEL_DSN=your_dsn_here
 * 5. composer require sentry/sentry-laravel
 */

return [
    'dsn' => env('SENTRY_LARAVEL_DSN'),

    'environment' => env('APP_ENV', 'production'),

    'traces_sample_rate' => env('SENTRY_TRACES_SAMPLE_RATE', 1.0),

    'attach_stacktrace' => true,

    'default_integrations' => true,

    'before_send' => null,

    'before_breadcrumb' => null,

    'transport' => 'curl',

    'max_breadcrumbs' => 50,

    'attach_metric' => true,

    'metrics' => [
        'enabled' => true,
        'endpoint' => null,
    ],

    'profiler' => [
        'enabled' => false,
        'sample_rate' => 0.1,
    ],

    'release' => env('APP_VERSION', '1.0.0'),

    /**
     * List of exception classes that should not be reported
     */
    'ignore_exceptions' => [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Illuminate\Validation\ValidationException::class,
        \Illuminate\Http\Exceptions\HttpResponseException::class,
        \Illuminate\Database\RecordsNotFoundException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
    ],

    /**
     * URLs that should not be reported
     */
    'ignore_transactions' => [
        '/health',
        '/status',
        '/ping',
    ],
];
