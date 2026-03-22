<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\KnowledgeBaseArticleData;
use Palach\Omnidesk\DTO\KnowledgeBaseCategoryData;
use Palach\Omnidesk\DTO\KnowledgeBaseSectionData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\DeleteKnowledgeBaseArticle\Payload as DeleteArticlePayload;
use Palach\Omnidesk\UseCases\V1\DeleteKnowledgeBaseCategory\Payload as DeleteCategoryPayload;
use Palach\Omnidesk\UseCases\V1\DeleteKnowledgeBaseSection\Payload as DeleteSectionPayload;
use Palach\Omnidesk\UseCases\V1\DisableArticle\Payload as DisableArticlePayload;
use Palach\Omnidesk\UseCases\V1\DisableCategory\Payload as DisableCategoryPayload;
use Palach\Omnidesk\UseCases\V1\DisableKnowledgeBaseArticle\Response as DisabledKnowledgeBaseArticleResponse;
use Palach\Omnidesk\UseCases\V1\DisableKnowledgeBaseCategory\Response as DisabledKnowledgeBaseCategoryResponse;
use Palach\Omnidesk\UseCases\V1\DisableKnowledgeBaseSection\Response as DisabledKnowledgeBaseSectionResponse;
use Palach\Omnidesk\UseCases\V1\DisableSection\Payload as DisableSectionPayload;
use Palach\Omnidesk\UseCases\V1\EnableArticle\Payload as EnableArticlePayload;
use Palach\Omnidesk\UseCases\V1\EnableCategory\Payload as EnableCategoryPayload;
use Palach\Omnidesk\UseCases\V1\EnableKnowledgeBaseArticle\Response as EnabledKnowledgeBaseArticleResponse;
use Palach\Omnidesk\UseCases\V1\EnableKnowledgeBaseCategory\Response as EnabledKnowledgeBaseCategoryResponse;
use Palach\Omnidesk\UseCases\V1\EnableKnowledgeBaseSection\Payload as EnableSectionPayload;
use Palach\Omnidesk\UseCases\V1\EnableKnowledgeBaseSection\Response as EnabledKnowledgeBaseSectionResponse;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticle\Payload as FetchKnowledgeBaseArticlePayload;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticle\Response as FetchKnowledgeBaseArticleResponse;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticleList\Payload as FetchKnowledgeBaseArticleListPayload;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticleList\Response as FetchKnowledgeBaseArticleListResponse;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategory\Payload as FetchKnowledgeBaseCategoryPayload;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategory\Response as FetchKnowledgeBaseCategoryResponse;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategoryList\Payload as FetchKnowledgeBaseCategoryListPayload;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategoryList\Response as FetchKnowledgeBaseCategoryListResponse;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSection\Payload as FetchKnowledgeBaseSectionPayload;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSection\Response as FetchKnowledgeBaseSectionResponse;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSectionList\Payload as FetchKnowledgeBaseSectionListPayload;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSectionList\Response as FetchKnowledgeBaseSectionListResponse;
use Palach\Omnidesk\UseCases\V1\MoveDownKnowledgeBaseArticle\Payload as MoveDownArticlePayload;
use Palach\Omnidesk\UseCases\V1\MoveDownKnowledgeBaseArticle\Response as MoveDownKnowledgeBaseArticleResponse;
use Palach\Omnidesk\UseCases\V1\MoveDownKnowledgeBaseCategory\Payload as MoveDownCategoryPayload;
use Palach\Omnidesk\UseCases\V1\MoveDownKnowledgeBaseCategory\Response as MoveDownKnowledgeBaseCategoryResponse;
use Palach\Omnidesk\UseCases\V1\MoveDownKnowledgeBaseSection\Payload as MoveDownSectionPayload;
use Palach\Omnidesk\UseCases\V1\MoveDownKnowledgeBaseSection\Response as MoveDownKnowledgeBaseSectionResponse;
use Palach\Omnidesk\UseCases\V1\MoveUpKnowledgeBaseArticle\Payload as MoveUpArticlePayload;
use Palach\Omnidesk\UseCases\V1\MoveUpKnowledgeBaseArticle\Response as MoveUpKnowledgeBaseArticleResponse;
use Palach\Omnidesk\UseCases\V1\MoveUpKnowledgeBaseCategory\Payload as MoveUpCategoryPayload;
use Palach\Omnidesk\UseCases\V1\MoveUpKnowledgeBaseCategory\Response as MoveUpKnowledgeBaseCategoryResponse;
use Palach\Omnidesk\UseCases\V1\MoveUpKnowledgeBaseSection\Payload as MoveUpSectionPayload;
use Palach\Omnidesk\UseCases\V1\MoveUpKnowledgeBaseSection\Response as MoveUpKnowledgeBaseSectionResponse;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseArticle\Payload as StoreKnowledgeBaseArticlePayload;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseArticle\Response as StoreKnowledgeBaseArticleResponse;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseCategory\Payload as StoreKnowledgeBaseCategoryPayload;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseCategory\Response as StoreKnowledgeBaseCategoryResponse;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseSection\Payload as StoreKnowledgeBaseSectionPayload;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseSection\Response as StoreKnowledgeBaseSectionResponse;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseArticle\Payload as UpdateKnowledgeBaseArticlePayload;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseArticle\Response as UpdateKnowledgeBaseArticleResponse;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseCategory\Payload as UpdateKnowledgeBaseCategoryPayload;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseCategory\Response as UpdateKnowledgeBaseCategoryResponse;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseSection\Payload as UpdateKnowledgeBaseSectionPayload;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseSection\Response as UpdateKnowledgeBaseSectionResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class KnowledgeBaseClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/kb_category.json';

    private const string SECTION_URL = '/api/kb_section.json';

    private const string SECTION_DETAIL_URL = '/api/kb_section/%s.json';

    private const string ARTICLE_URL = '/api/kb_article.json';

    private const string ARTICLE_DETAIL_URL = '/api/kb_article/%s.json';

    private const string CATEGORY_URL = '/api/kb_category/%s.json';

    private const string MOVE_UP_URL = '/api/kb_category/%s/moveup.json';

    private const string MOVE_DOWN_URL = '/api/kb_category/%s/movedown.json';

    private const string MOVE_UP_SECTION_URL = '/api/kb_section/%s/moveup.json';

    private const string MOVE_DOWN_SECTION_URL = '/api/kb_section/%s/movedown.json';

    private const string MOVE_UP_ARTICLE_URL = '/api/kb_article/%s/moveup.json';

    private const string MOVE_DOWN_ARTICLE_URL = '/api/kb_article/%s/movedown.json';

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
    public function storeSection(StoreKnowledgeBaseSectionPayload $payload): StoreKnowledgeBaseSectionResponse
    {
        $response = $this->transport->sendJson(Request::METHOD_POST, self::SECTION_URL, $payload->toArray());

        $kbSection = $this->extractArray('kb_section', $response);

        return new StoreKnowledgeBaseSectionResponse(
            kbSection: KnowledgeBaseSectionData::from($kbSection),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function storeArticle(StoreKnowledgeBaseArticlePayload $payload): StoreKnowledgeBaseArticleResponse
    {
        $response = $this->transport->sendJson(Request::METHOD_POST, self::ARTICLE_URL, $payload->toArray());

        $kbArticle = $this->extractArray('kb_article', $response);

        return new StoreKnowledgeBaseArticleResponse(
            kbArticle: KnowledgeBaseArticleData::from($kbArticle),
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
    public function getSection(FetchKnowledgeBaseSectionPayload $payload): FetchKnowledgeBaseSectionResponse
    {
        $url = sprintf(self::SECTION_DETAIL_URL, $payload->sectionId);

        $response = $this->transport->get($url, $payload->toQuery());

        $kbSection = $this->extractArray('kb_section', $response);

        return new FetchKnowledgeBaseSectionResponse(
            kbSection: KnowledgeBaseSectionData::from($kbSection),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchArticle(FetchKnowledgeBaseArticlePayload $payload): FetchKnowledgeBaseArticleResponse
    {
        $url = sprintf(self::ARTICLE_DETAIL_URL, $payload->articleId);

        $response = $this->transport->get($url, $payload->toQuery());

        $kbArticle = $this->extractArray('kb_article', $response);

        return new FetchKnowledgeBaseArticleResponse(
            kbArticle: KnowledgeBaseArticleData::from($kbArticle),
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
    public function fetchSectionList(FetchKnowledgeBaseSectionListPayload $payload): FetchKnowledgeBaseSectionListResponse
    {
        $response = $this->transport->get(self::SECTION_URL, $payload->toQuery());

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        $kbSections = collect($response)
            ->map(fn ($item) => KnowledgeBaseSectionData::from($item['kb_section']));

        return new FetchKnowledgeBaseSectionListResponse(
            kbSections: $kbSections,
            total: $total,
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchArticleList(FetchKnowledgeBaseArticleListPayload $payload): FetchKnowledgeBaseArticleListResponse
    {
        $response = $this->transport->get(self::ARTICLE_URL, $payload->toQuery());

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        $kbArticles = collect($response)
            ->map(fn ($item) => KnowledgeBaseArticleData::from($item['kb_article']));

        return new FetchKnowledgeBaseArticleListResponse(
            kbArticles: $kbArticles,
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
    public function updateSection(int $sectionId, UpdateKnowledgeBaseSectionPayload $payload): UpdateKnowledgeBaseSectionResponse
    {
        $url = sprintf(self::SECTION_DETAIL_URL, $sectionId);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, $payload->toArray());

        $kbSection = $this->extractArray('kb_section', $response);

        return new UpdateKnowledgeBaseSectionResponse(
            kbSection: KnowledgeBaseSectionData::from($kbSection),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function updateArticle(int $articleId, UpdateKnowledgeBaseArticlePayload $payload): UpdateKnowledgeBaseArticleResponse
    {
        $url = sprintf(self::ARTICLE_DETAIL_URL, $articleId);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, $payload->toArray());

        $kbArticle = $this->extractArray('kb_article', $response);

        return new UpdateKnowledgeBaseArticleResponse(
            kbArticle: KnowledgeBaseArticleData::from($kbArticle),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function disableSection(DisableSectionPayload $payload): DisabledKnowledgeBaseSectionResponse
    {
        $url = str_replace('.json', "/{$payload->sectionId}/disable.json", self::SECTION_URL);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, []);

        $kbSection = $this->extractArray('kb_section', $response);

        return new DisabledKnowledgeBaseSectionResponse(
            kbSection: KnowledgeBaseSectionData::from($kbSection),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function disableArticle(DisableArticlePayload $payload): DisabledKnowledgeBaseArticleResponse
    {
        $url = str_replace('.json', "/{$payload->articleId}/disable.json", self::ARTICLE_URL);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, []);

        $kbArticle = $this->extractArray('kb_article', $response);

        return new DisabledKnowledgeBaseArticleResponse(
            kbArticle: KnowledgeBaseArticleData::from($kbArticle),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function enableArticle(EnableArticlePayload $payload): EnabledKnowledgeBaseArticleResponse
    {
        $url = str_replace('.json', "/{$payload->articleId}/enable.json", self::ARTICLE_URL);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, []);

        $kbArticle = $this->extractArray('kb_article', $response);

        return new EnabledKnowledgeBaseArticleResponse(
            kbArticle: KnowledgeBaseArticleData::from($kbArticle),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function enableSection(EnableSectionPayload $payload): EnabledKnowledgeBaseSectionResponse
    {
        $url = str_replace('.json', "/{$payload->sectionId}/enable.json", self::SECTION_URL);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url);

        $kbSection = $this->extractArray('kb_section', $response);

        return new EnabledKnowledgeBaseSectionResponse(
            kbSection: KnowledgeBaseSectionData::from($kbSection),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function disableCategory(DisableCategoryPayload $payload): DisabledKnowledgeBaseCategoryResponse
    {
        $url = str_replace('.json', "/{$payload->categoryId}/disable.json", self::API_URL);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url);

        $kbCategory = $this->extractArray('kb_category', $response);

        return new DisabledKnowledgeBaseCategoryResponse(
            kbCategory: KnowledgeBaseCategoryData::from($kbCategory),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function enableCategory(EnableCategoryPayload $payload): EnabledKnowledgeBaseCategoryResponse
    {
        $url = sprintf(self::CATEGORY_URL, $payload->categoryId);
        $url = str_replace('.json', '/enable.json', $url);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url);

        $kbCategory = $this->extractArray('kb_category', $response);

        return new EnabledKnowledgeBaseCategoryResponse(
            kbCategory: KnowledgeBaseCategoryData::from($kbCategory),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function moveUpCategory(MoveUpCategoryPayload $payload): MoveUpKnowledgeBaseCategoryResponse
    {
        $url = sprintf(self::MOVE_UP_URL, $payload->categoryId);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url);

        $kbCategory = $this->extractArray('kb_category', $response);

        return new MoveUpKnowledgeBaseCategoryResponse(
            kbCategory: KnowledgeBaseCategoryData::from($kbCategory),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function moveDownCategory(MoveDownCategoryPayload $payload): MoveDownKnowledgeBaseCategoryResponse
    {
        $url = sprintf(self::MOVE_DOWN_URL, $payload->categoryId);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url);

        $kbCategory = $this->extractArray('kb_category', $response);

        return new MoveDownKnowledgeBaseCategoryResponse(
            kbCategory: KnowledgeBaseCategoryData::from($kbCategory),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function deleteCategory(DeleteCategoryPayload $payload): void
    {
        $url = sprintf(self::CATEGORY_URL, $payload->categoryId);

        $this->transport->sendJson(Request::METHOD_DELETE, $url);
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function deleteSection(DeleteSectionPayload $payload): void
    {
        $url = sprintf(self::SECTION_DETAIL_URL, $payload->sectionId);

        $this->transport->sendJson(Request::METHOD_DELETE, $url);
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function deleteArticle(DeleteArticlePayload $payload): void
    {
        $url = sprintf(self::ARTICLE_DETAIL_URL, $payload->articleId);

        $this->transport->sendJson(Request::METHOD_DELETE, $url);
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function moveUpSection(MoveUpSectionPayload $payload): MoveUpKnowledgeBaseSectionResponse
    {
        $url = sprintf(self::MOVE_UP_SECTION_URL, $payload->sectionId);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url);

        $kbSection = $this->extractArray('kb_section', $response);

        return new MoveUpKnowledgeBaseSectionResponse(
            kbSection: KnowledgeBaseSectionData::from($kbSection),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function moveUpArticle(MoveUpArticlePayload $payload): MoveUpKnowledgeBaseArticleResponse
    {
        $url = sprintf(self::MOVE_UP_ARTICLE_URL, $payload->articleId);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url);

        $kbArticle = $this->extractArray('kb_article', $response);

        return new MoveUpKnowledgeBaseArticleResponse(
            kbArticle: KnowledgeBaseArticleData::from($kbArticle),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function moveDownSection(MoveDownSectionPayload $payload): MoveDownKnowledgeBaseSectionResponse
    {
        $url = sprintf(self::MOVE_DOWN_SECTION_URL, $payload->sectionId);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url);

        $kbSection = $this->extractArray('kb_section', $response);

        return new MoveDownKnowledgeBaseSectionResponse(
            kbSection: KnowledgeBaseSectionData::from($kbSection),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function moveDownArticle(MoveDownArticlePayload $payload): MoveDownKnowledgeBaseArticleResponse
    {
        $url = sprintf(self::MOVE_DOWN_ARTICLE_URL, $payload->articleId);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url);

        $kbArticle = $this->extractArray('kb_article', $response);

        return new MoveDownKnowledgeBaseArticleResponse(
            kbArticle: KnowledgeBaseArticleData::from($kbArticle),
        );
    }
}
