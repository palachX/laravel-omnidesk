<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\KnowledgeBaseCategoryData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseCategory\Payload as StoreKnowledgeBaseCategoryPayload;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseCategory\Response as StoreKnowledgeBaseCategoryResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class KnowledgeBaseClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/kb_category.json';

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
}
