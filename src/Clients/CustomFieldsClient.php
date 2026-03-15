<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\CustomFieldData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\FetchCustomFieldList\Response as FetchCustomFieldListResponse;

final readonly class CustomFieldsClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/custom_fields.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchList(): FetchCustomFieldListResponse
    {
        $response = $this->transport->get(self::API_URL);

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $totalCount = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        $customFields = collect($response)
            ->map(fn ($item) => CustomFieldData::from($item['custom_field']));

        return new FetchCustomFieldListResponse(
            customFields: $customFields,
            totalCount: $totalCount,
        );
    }
}
