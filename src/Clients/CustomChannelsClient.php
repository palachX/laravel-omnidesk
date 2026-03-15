<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\CustomChannelData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\FetchCustomChannelList\Response as FetchCustomChannelListResponse;

final readonly class CustomChannelsClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/custom_channels.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchList(): FetchCustomChannelListResponse
    {
        $response = $this->transport->get(self::API_URL);

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $totalCount = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        $customChannels = collect($response)
            ->map(fn ($item) => CustomChannelData::from($item['custom_channel']));

        return new FetchCustomChannelListResponse(
            customChannels: $customChannels,
            totalCount: $totalCount,
        );
    }
}
