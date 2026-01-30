<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Support\Testing\WebhookMessageNew;

use Palach\Omnidesk\Interfaces\WebhookHandlerInterface;
use Palach\Omnidesk\Models\OmniWebhook;
use Spatie\LaravelData\Data;

final class WebhookMessageNewHandler implements WebhookHandlerInterface
{
    /**
     * @param  WebhookMessageNewDataInput  $dataInput
     */
    public function handle(Data $dataInput, OmniWebhook $omniWebhook): void {}
}
