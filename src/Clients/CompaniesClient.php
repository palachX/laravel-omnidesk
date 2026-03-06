<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\CompanyData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\FetchCompanyList\Payload as FetchCompanyListPayload;
use Palach\Omnidesk\UseCases\V1\FetchCompanyList\Response as FetchCompanyListResponse;
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

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchCompanyList(?FetchCompanyListPayload $payload = null): FetchCompanyListResponse
    {
        $response = $this->transport->get(self::API_URL, $payload?->toQuery() ?? []);

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        $companies = collect($response)
            ->map(fn ($item) => CompanyData::from($item['company']));

        return new FetchCompanyListResponse(
            companies: $companies,
            total: $total,
        );
    }
}
