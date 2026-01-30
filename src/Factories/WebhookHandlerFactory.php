<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Factories;

use Illuminate\Support\Facades\App;
use InvalidArgumentException;
use Palach\Omnidesk\DTO\OmnideskConfig;
use Palach\Omnidesk\Interfaces\WebhookHandlerInterface;

final readonly class WebhookHandlerFactory
{
    public function __construct(
        private OmnideskConfig $config
    ) {}

    public function make(string $type): WebhookHandlerInterface
    {
        $webhooksConfig = $this->config->webhooks;
        $webhook = $webhooksConfig->where('type', $type)->first();

        if (empty($webhook)) {
            throw new InvalidArgumentException('Not fount webhook type');
        }

        /** @var class-string<WebhookHandlerInterface> $handler */
        $handler = $webhook->handler;

        if (! class_exists($handler)) {
            throw new InvalidArgumentException("Handler class {$handler} does not exist");
        }

        if (! is_subclass_of($handler, WebhookHandlerInterface::class)) {
            throw new InvalidArgumentException('Handler not implement '.WebhookHandlerInterface::class);
        }

        /** @var WebhookHandlerInterface */
        return App::make($handler);
    }
}
