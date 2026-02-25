<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Factories;

use InvalidArgumentException;
use Palach\Omnidesk\DTO\OmnideskConfig;
use Spatie\LaravelData\Data;

final readonly class WebhookHandlerDataInputFactory
{
    public function __construct(
        private OmnideskConfig $config
    ) {}

    /**
     * @param  array<mixed>  $object
     */
    public function make(string $type, array $object): Data
    {
        $webhooksConfig = $this->config->webhooks;
        $webhook = $webhooksConfig->where('type', $type)->first();

        if (empty($webhook)) {
            throw new InvalidArgumentException('Not fount webhook type');
        }

        /** @var class-string<Data> $data */
        $data = $webhook->data;

        if (! class_exists($data)) {
            throw new InvalidArgumentException("Data input class {$data} does not exist");
        }

        if (! is_subclass_of($data, Data::class)) {
            throw new InvalidArgumentException('Data input not implement '.Data::class);
        }

        /** @var Data */
        return $data::validateAndCreate(array_filter(
            $object,
            static fn ($value) => $value !== null
        ));
    }
}
