<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Transport;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\OmnideskConfig;

final readonly class OmnideskTransport
{
    public function __construct(
        private OmnideskConfig $config,
    ) {}

    private function base(): PendingRequest
    {
        return Http::withBasicAuth(
            $this->config->email,
            $this->config->apiKey
        )
            ->baseUrl($this->config->host)
            ->withHeaders(['Accept' => 'application/json']);
    }

    /**
     * @param  array<mixed>  $data
     *
     * @throws ConnectionException
     * @throws RequestException
     */
    public function sendJson(string $method, string $url, array $data = []): mixed
    {
        return $this->base()
            ->send($method, $url, ['json' => $data])
            ->throw()
            ->json();
    }

    /**
     * @param  array<mixed>  $data
     *
     * @throws ConnectionException
     * @throws RequestException
     */
    public function sendMultipart(string $method, string $url, array $data): mixed
    {
        return $this->base()
            ->send($method, $url, ['multipart' => $data])
            ->throw()
            ->json();
    }

    /**
     * @param  array<mixed>  $query
     *
     * @throws ConnectionException
     * @throws RequestException
     */
    public function get(string $url, array $query): mixed
    {
        return $this->base()
            ->withQueryParameters($query)
            ->get($url)
            ->throw()
            ->json();
    }
}
