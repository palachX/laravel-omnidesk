<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\StaffData;
use Palach\Omnidesk\DTO\StaffRoleData;
use Palach\Omnidesk\DTO\StaffStatusData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\FetchStaff\Payload as FetchStaffPayload;
use Palach\Omnidesk\UseCases\V1\FetchStaff\Response as FetchStaffResponse;
use Palach\Omnidesk\UseCases\V1\FetchStaffList\Payload as FetchStaffListPayload;
use Palach\Omnidesk\UseCases\V1\FetchStaffList\Response as FetchStaffListResponse;
use Palach\Omnidesk\UseCases\V1\FetchStaffRoleList\Response as FetchStaffRoleListResponse;
use Palach\Omnidesk\UseCases\V1\FetchStaffStatusList\Response as FetchStaffStatusListResponse;
use Palach\Omnidesk\UseCases\V1\StoreStaff\Payload as StoreStaffPayload;
use Palach\Omnidesk\UseCases\V1\StoreStaff\Response as StoreStaffResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class StaffsClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/staff.json';

    private const string STAFF_URL = '/api/staff/%s.json';

    private const string STAFF_ROLES_URL = '/api/staff_roles.json';

    private const string STAFF_STATUSES_URL = '/api/staff_statuses.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function store(StoreStaffPayload $payload): StoreStaffResponse
    {
        $response = $this->transport->sendJson(Request::METHOD_POST, self::API_URL, ['staff' => $payload->toArray()]);

        $staff = $this->extract('staff', $response);

        return new StoreStaffResponse(
            staff: StaffData::from($staff),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchStaff(FetchStaffPayload $payload): FetchStaffResponse
    {
        $url = sprintf(self::STAFF_URL, $payload->staffId);

        $response = $this->transport->get($url, $payload->toQuery());

        $staff = $this->extract('staff', $response);

        return new FetchStaffResponse(
            staff: StaffData::from($staff),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchStaffList(?FetchStaffListPayload $payload = null): FetchStaffListResponse
    {
        $response = $this->transport->get(self::API_URL, $payload?->toQuery() ?? []);

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        $staffs = collect($response)
            ->map(fn ($item) => StaffData::from($item['staff']));

        return new FetchStaffListResponse(
            staffs: $staffs,
            total: $total,
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchStaffRoleList(): FetchStaffRoleListResponse
    {
        $response = $this->transport->get(self::STAFF_ROLES_URL);

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $count = isset($response['count']) ? (int) $response['count'] : 0;

        unset($response['count']);

        $staffRoles = collect($response)
            ->map(fn ($item) => StaffRoleData::from($item['staff_roles']));

        return new FetchStaffRoleListResponse(
            staffRoles: $staffRoles,
            count: $count,
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchStaffStatusList(): FetchStaffStatusListResponse
    {
        $response = $this->transport->get(self::STAFF_STATUSES_URL);

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $count = isset($response['count']) ? (int) $response['count'] : 0;

        unset($response['count']);

        $staffStatuses = collect($response)
            ->map(fn ($item) => StaffStatusData::from($item['staff_statuses']));

        return new FetchStaffStatusListResponse(
            staffStatuses: $staffStatuses,
            count: $count,
        );
    }
}
