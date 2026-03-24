<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\IdeaCategoryData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
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
}
