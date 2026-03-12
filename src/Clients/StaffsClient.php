<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\StaffData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\StoreStaff\Payload as StoreStaffPayload;
use Palach\Omnidesk\UseCases\V1\StoreStaff\Response as StoreStaffResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class StaffsClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/staff.json';

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
}
