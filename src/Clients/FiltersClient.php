<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\FilterData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\FetchFilterList\Response as FetchFilterListResponse;

final readonly class FiltersClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/filters.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchList(): FetchFilterListResponse
    {
        $response = $this->transport->get(self::API_URL);

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $totalCount = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        $filters = collect($response)
            ->map(fn ($item) => FilterData::from($item['filter']));

        return new FetchFilterListResponse(
            filters: $filters,
            totalCount: $totalCount,
        );
    }
}
