<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\IdeaCategoryData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\DisableIdeaCategory\Payload as DisableIdeaCategoryPayload;
use Palach\Omnidesk\UseCases\V1\DisableIdeaCategory\Response as DisableIdeaCategoryResponse;
use Palach\Omnidesk\UseCases\V1\FetchIdeaCategory\Payload as FetchIdeaCategoryPayload;
use Palach\Omnidesk\UseCases\V1\FetchIdeaCategory\Response as FetchIdeaCategoryResponse;
use Palach\Omnidesk\UseCases\V1\FetchIdeaCategoryList\Payload as FetchIdeaCategoryListPayload;
use Palach\Omnidesk\UseCases\V1\FetchIdeaCategoryList\Response as FetchIdeaCategoryListResponse;
use Palach\Omnidesk\UseCases\V1\StoreIdeaCategory\Payload as StoreIdeaCategoryPayload;
use Palach\Omnidesk\UseCases\V1\StoreIdeaCategory\Response as StoreIdeaCategoryResponse;
use Palach\Omnidesk\UseCases\V1\UpdateIdeaCategory\Payload as UpdateIdeaCategoryPayload;
use Palach\Omnidesk\UseCases\V1\UpdateIdeaCategory\Response as UpdateIdeaCategoryResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class IdeaCategoriesClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/ideas_category.json';

    private const string IDEA_CATEGORY_URL = '/api/ideas_category/%s.json';

    private const string DISABLE_URL = '/api/ideas_category/%s/disable.json';

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
    public function getIdeaCategory(FetchIdeaCategoryPayload $payload): FetchIdeaCategoryResponse
    {
        $url = sprintf(self::IDEA_CATEGORY_URL, $payload->categoryId);

        $response = $this->transport->get($url);

        $category = $this->extractArray('ideas_category', $response);

        return new FetchIdeaCategoryResponse(
            ideasCategory: IdeaCategoryData::from($category),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function update(int $categoryId, UpdateIdeaCategoryPayload $payload): UpdateIdeaCategoryResponse
    {
        $url = sprintf(self::IDEA_CATEGORY_URL, $categoryId);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, $payload->toArray());

        $category = $this->extractArray('ideas_category', $response);

        return new UpdateIdeaCategoryResponse(
            ideasCategory: IdeaCategoryData::from($category),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function disable(DisableIdeaCategoryPayload $payload): DisableIdeaCategoryResponse
    {
        $url = sprintf(self::DISABLE_URL, $payload->categoryId);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url);

        $category = $this->extractArray('ideas_category', $response);

        return new DisableIdeaCategoryResponse(
            ideasCategory: IdeaCategoryData::from($category),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchList(FetchIdeaCategoryListPayload $payload): FetchIdeaCategoryListResponse
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

        return new FetchIdeaCategoryListResponse(
            ideaCategories: $categories,
            total: $total,
        );
    }
}
