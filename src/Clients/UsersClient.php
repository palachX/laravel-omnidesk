<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\UserData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\BlockUser\Payload as BlockUserPayload;
use Palach\Omnidesk\UseCases\V1\BlockUser\Response as BlockUserResponse;
use Palach\Omnidesk\UseCases\V1\DeleteUser\Payload as DeleteUserPayload;
use Palach\Omnidesk\UseCases\V1\DisableUser\Payload as DisableUserPayload;
use Palach\Omnidesk\UseCases\V1\DisableUser\Response as DisableUserResponse;
use Palach\Omnidesk\UseCases\V1\FetchUser\Payload as FetchUserPayload;
use Palach\Omnidesk\UseCases\V1\FetchUser\Response as FetchUserResponse;
use Palach\Omnidesk\UseCases\V1\FetchUserIdentification\Payload as FetchUserIdentificationPayload;
use Palach\Omnidesk\UseCases\V1\FetchUserIdentification\Response as FetchUserIdentificationResponse;
use Palach\Omnidesk\UseCases\V1\FetchUserList\Payload as FetchUserListPayload;
use Palach\Omnidesk\UseCases\V1\FetchUserList\Response as FetchUserListResponse;
use Palach\Omnidesk\UseCases\V1\LinkUser\Payload as LinkUserPayload;
use Palach\Omnidesk\UseCases\V1\LinkUser\Response as LinkUserResponse;
use Palach\Omnidesk\UseCases\V1\RecoveryUser\Payload as RecoveryUserPayload;
use Palach\Omnidesk\UseCases\V1\RecoveryUser\Response as RecoveryUserResponse;
use Palach\Omnidesk\UseCases\V1\StoreUser\Payload as StoreUserPayload;
use Palach\Omnidesk\UseCases\V1\StoreUser\Response as StoreUserResponse;
use Palach\Omnidesk\UseCases\V1\UnlinkUser\Payload as UnlinkUserPayload;
use Palach\Omnidesk\UseCases\V1\UnlinkUser\Response as UnlinkUserResponse;
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

        $user = $this->extractArray('user', $response);

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

        $user = $this->extractArray('user', $response);

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

        $user = $this->extractArray('user', $response);

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

        $code = $this->extractString('code', $response);

        return new FetchUserIdentificationResponse(
            code: $code,
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function linkUser(int $userId, LinkUserPayload $payload): LinkUserResponse
    {
        $url = str_replace('.json', "/$userId/link.json", self::API_URL);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, $payload->toArray());

        $user = $this->extractArray('user', $response);

        return new LinkUserResponse(
            user: UserData::from($user),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function disableUser(DisableUserPayload $payload): DisableUserResponse
    {
        $url = str_replace('.json', "/{$payload->userId}/disable.json", self::API_URL);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, []);

        $user = $this->extractArray('user', $response);

        return new DisableUserResponse(
            user: UserData::from($user),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function blockUser(BlockUserPayload $payload): BlockUserResponse
    {
        $url = str_replace('.json', "/{$payload->userId}/block.json", self::API_URL);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, []);

        $user = $this->extractArray('user', $response);

        return new BlockUserResponse(
            user: UserData::from($user),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function recoveryUser(RecoveryUserPayload $payload): RecoveryUserResponse
    {
        $url = str_replace('.json', "/{$payload->userId}/restore.json", self::API_URL);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, []);

        $user = $this->extractArray('user', $response);

        return new RecoveryUserResponse(
            user: UserData::from($user),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function deleteUser(DeleteUserPayload $payload): void
    {
        $url = str_replace('.json', "/{$payload->userId}.json", self::API_URL);
        $this->transport->sendJson(Request::METHOD_DELETE, $url, []);
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function unlinkUser(int $userId, UnlinkUserPayload $payload): UnlinkUserResponse
    {
        $url = str_replace('.json', "/$userId/unlink.json", self::API_URL);
        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, $payload->toArray());

        $user = $this->extractArray('user', $response);

        return new UnlinkUserResponse(
            user: UserData::from($user),
        );
    }
}
