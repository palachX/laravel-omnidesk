<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\UserData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\FetchUserList\Payload as FetchUserListPayload;
use Palach\Omnidesk\UseCases\V1\FetchUserList\Response as FetchUserListResponse;
use Palach\Omnidesk\UseCases\V1\StoreUser\Payload as StoreUserPayload;
use Palach\Omnidesk\UseCases\V1\StoreUser\Response as StoreUserResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class UsersClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/users.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function store(StoreUserPayload $payload): StoreUserResponse
    {
        $response = $this->transport->sendJson(Request::METHOD_POST, self::API_URL, $payload->toArray());

        $user = $this->extract('user', $response);

        return new StoreUserResponse(
            user: UserData::from($user),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchList(FetchUserListPayload $payload): FetchUserListResponse
    {
        $response = $this->transport->get(self::API_URL, $payload->toQuery());

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        $users = collect($response)
            ->map(fn ($item) => UserData::from($item['user']));

        return new FetchUserListResponse(
            users: $users,
            total: $total,
        );
    }
}
