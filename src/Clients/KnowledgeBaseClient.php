<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\KnowledgeBaseCategoryData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\DisabledKnowledgeBaseCategory\Response as DisabledKnowledgeBaseCategoryResponse;
use Palach\Omnidesk\UseCases\V1\EnabledKnowledgeBaseCategory\Response as EnabledKnowledgeBaseCategoryResponse;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategory\Payload as FetchKnowledgeBaseCategoryPayload;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategory\Response as FetchKnowledgeBaseCategoryResponse;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategoryList\Payload as FetchKnowledgeBaseCategoryListPayload;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategoryList\Response as FetchKnowledgeBaseCategoryListResponse;
use Palach\Omnidesk\UseCases\V1\MoveDownKnowledgeBaseCategory\Response as MoveDownKnowledgeBaseCategoryResponse;
use Palach\Omnidesk\UseCases\V1\MoveUpKnowledgeBaseCategory\Response as MoveUpKnowledgeBaseCategoryResponse;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseCategory\Payload as StoreKnowledgeBaseCategoryPayload;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseCategory\Response as StoreKnowledgeBaseCategoryResponse;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseCategory\Payload as UpdateKnowledgeBaseCategoryPayload;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseCategory\Response as UpdateKnowledgeBaseCategoryResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class KnowledgeBaseClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/kb_category.json';

    private const string CATEGORY_URL = '/api/kb_category/%s.json';

    private const string MOVE_UP_URL = '/api/kb_category/%s/moveup.json';

    private const string MOVE_DOWN_URL = '/api/kb_category/%s/movedown.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function storeCategory(StoreKnowledgeBaseCategoryPayload $payload): StoreKnowledgeBaseCategoryResponse
    {
        $response = $this->transport->sendJson(Request::METHOD_POST, self::API_URL, $payload->toArray());

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

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function updateCategory(int $categoryId, UpdateKnowledgeBaseCategoryPayload $payload): UpdateKnowledgeBaseCategoryResponse
    {
        $url = sprintf(self::CATEGORY_URL, $categoryId);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, $payload->toArray());

        $kbCategory = $this->extractArray('kb_category', $response);

        return new UpdateKnowledgeBaseCategoryResponse(
            kbCategory: KnowledgeBaseCategoryData::from($kbCategory),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function disableCategory(int $categoryId): DisabledKnowledgeBaseCategoryResponse
    {
        $url = str_replace('.json', "/$categoryId/disable.json", self::API_URL);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, []);

        $kbCategory = $this->extractArray('kb_category', $response);

        return new DisabledKnowledgeBaseCategoryResponse(
            kbCategory: KnowledgeBaseCategoryData::from($kbCategory),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function enableCategory(int $categoryId): EnabledKnowledgeBaseCategoryResponse
    {
        $url = sprintf(self::CATEGORY_URL, $categoryId);
        $url = str_replace('.json', '/enable.json', $url);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, []);

        $kbCategory = $this->extractArray('kb_category', $response);

        return new EnabledKnowledgeBaseCategoryResponse(
            kbCategory: KnowledgeBaseCategoryData::from($kbCategory),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function moveUpCategory(int $categoryId): MoveUpKnowledgeBaseCategoryResponse
    {
        $url = sprintf(self::MOVE_UP_URL, $categoryId);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, []);

        $kbCategory = $this->extractArray('kb_category', $response);

        return new MoveUpKnowledgeBaseCategoryResponse(
            kbCategory: KnowledgeBaseCategoryData::from($kbCategory),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function moveDownCategory(int $categoryId): MoveDownKnowledgeBaseCategoryResponse
    {
        $url = sprintf(self::MOVE_DOWN_URL, $categoryId);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, []);

        $kbCategory = $this->extractArray('kb_category', $response);

        return new MoveDownKnowledgeBaseCategoryResponse(
            kbCategory: KnowledgeBaseCategoryData::from($kbCategory),
        );
    }
}
