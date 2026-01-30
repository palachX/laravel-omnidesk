<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Palach\Omnidesk\Http\Controllers\WebhookController;

if ($webhookUrl = config('omnidesk.webhook_url', config('omnidesk.webhook.url', '/omnidesk/{omniWebhook}/webhook'))) {

    Route::post($webhookUrl, [WebhookController::class, 'handle'])
        ->middleware('api')
        ->middleware(config('omnidesk.webhook.middleware', []))
        ->name('omnidesk.webhook');

}
