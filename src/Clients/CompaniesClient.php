<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\CompanyData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\StoreCompany\Payload as StoreCompanyPayload;
use Palach\Omnidesk\UseCases\V1\StoreCompany\Response as StoreCompanyResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class CompaniesClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/companies.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function store(StoreCompanyPayload $payload): StoreCompanyResponse
    {
        $response = $this->transport->sendJson(Request::METHOD_POST, self::API_URL, ['company' => $payload->toArray()]);

        $company = $this->extract('company', $response);

        return new StoreCompanyResponse(
            company: CompanyData::from($company),
        );
    }
}
