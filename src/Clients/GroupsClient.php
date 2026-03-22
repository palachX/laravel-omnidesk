<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\GroupData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\DeleteGroup\Payload as DeleteGroupPayload;
use Palach\Omnidesk\UseCases\V1\DisableGroup\Payload as DisableGroupPayload;
use Palach\Omnidesk\UseCases\V1\DisableGroup\Response as DisabledGroupResponse;
use Palach\Omnidesk\UseCases\V1\EnableGroup\Payload as EnableGroupPayload;
use Palach\Omnidesk\UseCases\V1\EnableGroup\Response as EnableGroupResponse;
use Palach\Omnidesk\UseCases\V1\FetchGroup\Payload as FetchGroupPayload;
use Palach\Omnidesk\UseCases\V1\FetchGroup\Response as FetchGroupResponse;
use Palach\Omnidesk\UseCases\V1\FetchGroupList\Payload as FetchGroupListPayload;
use Palach\Omnidesk\UseCases\V1\FetchGroupList\Response as FetchGroupListResponse;
use Palach\Omnidesk\UseCases\V1\StoreGroup\Payload as StoreGroupPayload;
use Palach\Omnidesk\UseCases\V1\StoreGroup\Response as StoreGroupResponse;
use Palach\Omnidesk\UseCases\V1\UpdateGroup\Payload as UpdateGroupPayload;
use Palach\Omnidesk\UseCases\V1\UpdateGroup\Response as UpdateGroupResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class GroupsClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/groups.json';

    private const string GROUP_URL = '/api/groups/%s.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function getGroup(FetchGroupPayload $payload): FetchGroupResponse
    {
        $url = sprintf(self::GROUP_URL, $payload->groupId);

        $response = $this->transport->get($url);

        $group = $this->extractArray('group', $response);

        return new FetchGroupResponse(
            group: GroupData::from($group),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function store(StoreGroupPayload $payload): StoreGroupResponse
    {
        $response = $this->transport->sendJson(Request::METHOD_POST, self::API_URL, ['group' => $payload->toArray()]);

        $group = $this->extractArray('group', $response);

        return new StoreGroupResponse(
            group: GroupData::from($group),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function update(int $groupId, UpdateGroupPayload $payload): UpdateGroupResponse
    {
        $url = sprintf(self::GROUP_URL, $groupId);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, $payload->toArray());

        $group = $this->extractArray('group', $response);

        return new UpdateGroupResponse(
            group: GroupData::from($group),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchList(FetchGroupListPayload $payload): FetchGroupListResponse
    {
        $response = $this->transport->get(self::API_URL, $payload->toQuery());

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        $groups = collect($response)
            ->map(fn ($item) => GroupData::from($item['group']));

        return new FetchGroupListResponse(
            groups: $groups,
            total: $total,
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function disableGroup(DisableGroupPayload $payload): DisabledGroupResponse
    {
        $url = sprintf(self::GROUP_URL, $payload->groupId);
        $url = str_replace('.json', '/disable.json', $url);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, ['group' => ['replace_group_id' => $payload->replaceGroupId]]);

        $group = $this->extractArray('group', $response);

        return new DisabledGroupResponse(
            group: GroupData::from($group),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function enableGroup(EnableGroupPayload $payload): EnableGroupResponse
    {
        $url = sprintf(self::GROUP_URL, $payload->groupId);
        $url = str_replace('.json', '/enable.json', $url);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, []);

        $group = $this->extractArray('group', $response);

        return new EnableGroupResponse(
            group: GroupData::from($group),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function deleteGroup(int $groupId, DeleteGroupPayload $payload): void
    {
        $url = sprintf(self::GROUP_URL, $groupId);

        $this->transport->sendJson(Request::METHOD_DELETE, $url, ['group' => $payload->toArray()]);
    }
}
