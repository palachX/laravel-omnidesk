<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Interfaces;

use Palach\Omnidesk\Models\OmniWebhook;
use Spatie\LaravelData\Data;

interface WebhookHandlerInterface
{
    public function handle(Data $dataInput, OmniWebhook $omniWebhook): void;
}
