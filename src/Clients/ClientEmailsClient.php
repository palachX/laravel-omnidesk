<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\ClientEmailData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\FetchClientEmailList\Response as FetchClientEmailListResponse;

final readonly class ClientEmailsClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/client_emails.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchList(): FetchClientEmailListResponse
    {
        $response = $this->transport->get(self::API_URL);

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $totalCount = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        $clientEmails = collect($response)
            ->map(fn ($item) => ClientEmailData::from($item['client_emails']));

        return new FetchClientEmailListResponse(
            clientEmails: $clientEmails,
            totalCount: $totalCount,
        );
    }
}
