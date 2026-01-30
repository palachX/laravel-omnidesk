<?php

declare(strict_types=1);

return [
    'host' => env('OMNI_HOST', 'http://localhost.omnidesk.ru'),

    'email' => env('OMNI_EMAIL', 'example@example.com'),

    'api_key' => env('OMNI_API_KEY', 'api_key'),

    /**
     * Without {omniWebhook} error
     */
    'webhook_url' => env('OMNI_WEBHOOK_URL', 'webhook_url'),

    'webhooks' => [],

    'webhook' => [
        'middleware' => [],
    ],
];
