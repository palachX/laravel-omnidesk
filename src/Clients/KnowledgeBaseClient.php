<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\KnowledgeBaseCategoryData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategory\Payload as FetchKnowledgeBaseCategoryPayload;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategory\Response as FetchKnowledgeBaseCategoryResponse;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategoryList\Payload as FetchKnowledgeBaseCategoryListPayload;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategoryList\Response as FetchKnowledgeBaseCategoryListResponse;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseCategory\Payload as StoreKnowledgeBaseCategoryPayload;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseCategory\Response as StoreKnowledgeBaseCategoryResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class KnowledgeBaseClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/kb_category.json';

    private const string CATEGORY_URL = '/api/kb_category/%s.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function storeCategory(StoreKnowledgeBaseCategoryPayload $payload): StoreKnowledgeBaseCategoryResponse
    {
        $response = $this->transport->sendJson(Request::METHOD_POST, self::API_URL, ['kb_category' => $payload->toArray()]);

        $kbCategory = $this->extractArray('kb_category', $response);

        return new StoreKnowledgeBaseCategoryResponse(
            kbCategory: KnowledgeBaseCategoryData::from($kbCategory),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchCategory(FetchKnowledgeBaseCategoryPayload $payload): FetchKnowledgeBaseCategoryResponse
    {
        $url = sprintf(self::CATEGORY_URL, $payload->categoryId);

        $response = $this->transport->get($url, $payload->toQuery());

        $kbCategory = $this->extractArray('kb_category', $response);

        return new FetchKnowledgeBaseCategoryResponse(
            kbCategory: KnowledgeBaseCategoryData::from($kbCategory),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchList(FetchKnowledgeBaseCategoryListPayload $payload): FetchKnowledgeBaseCategoryListResponse
    {
        $response = $this->transport->get(self::API_URL, $payload->toQuery());

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        $kbCategories = collect($response)
            ->map(fn ($item) => KnowledgeBaseCategoryData::from($item['kb_category']));

        return new FetchKnowledgeBaseCategoryListResponse(
            kbCategories: $kbCategories,
            total: $total,
        );
    }
}
