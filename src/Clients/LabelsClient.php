<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\StoreLabel\Payload as StoreLabelPayload;
use Palach\Omnidesk\UseCases\V1\StoreLabel\Response as StoreLabelResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class LabelsClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/labels.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function store(StoreLabelPayload $payload): StoreLabelResponse
    {
        $response = $this->transport->sendJson(
            Request::METHOD_POST,
            self::API_URL,
            $payload->toArray(),
        );

        return StoreLabelResponse::from($response);
    }
}
