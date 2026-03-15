<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\LanguageData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\FetchLanguageList\Response as FetchLanguageListResponse;

final readonly class LanguagesClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/languages.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchList(): FetchLanguageListResponse
    {
        $response = $this->transport->get(self::API_URL);

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $totalCount = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        $languages = collect($response)
            ->map(fn ($item) => LanguageData::from($item['language']));

        return new FetchLanguageListResponse(
            languages: $languages,
            totalCount: $totalCount,
        );
    }
}
