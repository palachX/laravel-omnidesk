<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Palach\Omnidesk\Factories\WebhookHandlerDataInputFactory;
use Palach\Omnidesk\Factories\WebhookHandlerFactory;
use Palach\Omnidesk\Http\Requests\WebhookRequest;
use Palach\Omnidesk\Models\OmniWebhook;

final class WebhookController
{
    public function handle(OmniWebhook $omniWebhook, WebhookRequest $request, WebhookHandlerFactory $factory, WebhookHandlerDataInputFactory $dataInputFactory): JsonResponse
    {
        $type = $request->type;

        $factory->make($type)->handle($dataInputFactory->make($type, $request->object), $omniWebhook);

        return new JsonResponse(['result' => 'ok'], 200);
    }
}
