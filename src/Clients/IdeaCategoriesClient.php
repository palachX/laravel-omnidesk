<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\IdeaCategoryData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\FetchIdeaCategoryList\Payload as FetchIdeaCategoryPayload;
use Palach\Omnidesk\UseCases\V1\FetchIdeaCategoryList\Response as FetchIdeaCategoryResponse;
use Palach\Omnidesk\UseCases\V1\StoreIdeaCategory\Payload as StoreIdeaCategoryPayload;
use Palach\Omnidesk\UseCases\V1\StoreIdeaCategory\Response as StoreIdeaCategoryResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class IdeaCategoriesClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/ideas_category.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function store(StoreIdeaCategoryPayload $payload): StoreIdeaCategoryResponse
    {
        $response = $this->transport->sendJson(Request::METHOD_POST, self::API_URL, $payload->toArray());

        $category = $this->extractArray('ideas_category', $response);

        return new StoreIdeaCategoryResponse(
            ideasCategory: IdeaCategoryData::from($category),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchList(FetchIdeaCategoryPayload $payload): FetchIdeaCategoryResponse
    {
        $response = $this->transport->get(self::API_URL, $payload->toQuery());

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        $categories = collect($response)
            ->filter(fn ($item) => is_array($item) && isset($item['ideas_category']))
            ->map(fn ($item) => IdeaCategoryData::from($item['ideas_category']));

        return new FetchIdeaCategoryResponse(
            ideaCategories: $categories,
            total: $total,
        );
    }
}
