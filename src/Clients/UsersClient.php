<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\UserData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\FetchUser\Payload as FetchUserPayload;
use Palach\Omnidesk\UseCases\V1\FetchUser\Response as FetchUserResponse;
use Palach\Omnidesk\UseCases\V1\FetchUserIdentification\Payload as FetchUserIdentificationPayload;
use Palach\Omnidesk\UseCases\V1\FetchUserIdentification\Response as FetchUserIdentificationResponse;
use Palach\Omnidesk\UseCases\V1\FetchUserList\Payload as FetchUserListPayload;
use Palach\Omnidesk\UseCases\V1\FetchUserList\Response as FetchUserListResponse;
use Palach\Omnidesk\UseCases\V1\StoreUser\Payload as StoreUserPayload;
use Palach\Omnidesk\UseCases\V1\StoreUser\Response as StoreUserResponse;
use Palach\Omnidesk\UseCases\V1\UpdateUser\Payload as UpdateUserPayload;
use Palach\Omnidesk\UseCases\V1\UpdateUser\Response as UpdateUserResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class UsersClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/users.json';

    private const string API_URL_IDENTIFICATION = '/api/users/identification.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetch(FetchUserPayload $payload): FetchUserResponse
    {
        $url = str_replace('.json', "/{$payload->user->userId}.json", self::API_URL);
        $response = $this->transport->get($url);

        $user = $this->extract('user', $response);

        return new FetchUserResponse(
            user: UserData::from($user),
        );
    }

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
    public function update(int $userId, UpdateUserPayload $payload): UpdateUserResponse
    {
        $url = str_replace('.json', "/{$userId}.json", self::API_URL);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, $payload->toArray());

        $user = $this->extract('user', $response);

        return new UpdateUserResponse(
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

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchUserIdentification(FetchUserIdentificationPayload $payload): FetchUserIdentificationResponse
    {
        $response = $this->transport->sendJson(Request::METHOD_POST, self::API_URL_IDENTIFICATION, $payload->toArray());

        /** @var string $code */
        $code = $this->extract('code', $response);

        return new FetchUserIdentificationResponse(
            code: $code,
        );
    }
}
