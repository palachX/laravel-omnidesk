<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\GroupData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\StoreGroup\Payload as StoreGroupPayload;
use Palach\Omnidesk\UseCases\V1\StoreGroup\Response as StoreGroupResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class GroupsClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/groups.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function store(StoreGroupPayload $payload): StoreGroupResponse
    {
        $response = $this->transport->sendJson(Request::METHOD_POST, self::API_URL, ['group' => $payload->toArray()]);

        $group = $this->extract('group', $response);

        return new StoreGroupResponse(
            group: GroupData::from($group),
        );
    }
}
