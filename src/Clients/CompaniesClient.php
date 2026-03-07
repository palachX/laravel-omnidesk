<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\CompanyData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\BlockCompany\Response as BlockCompanyResponse;
use Palach\Omnidesk\UseCases\V1\DeleteCompany\Response as DeleteCompanyResponse;
use Palach\Omnidesk\UseCases\V1\DisabledCompany\Response as DisabledCompanyResponse;
use Palach\Omnidesk\UseCases\V1\FetchCompany\Payload as FetchCompanyPayload;
use Palach\Omnidesk\UseCases\V1\FetchCompany\Response as FetchCompanyResponse;
use Palach\Omnidesk\UseCases\V1\FetchCompanyList\Payload as FetchCompanyListPayload;
use Palach\Omnidesk\UseCases\V1\FetchCompanyList\Response as FetchCompanyListResponse;
use Palach\Omnidesk\UseCases\V1\RecoveryCompany\Response as RecoveryCompanyResponse;
use Palach\Omnidesk\UseCases\V1\StoreCompany\Payload as StoreCompanyPayload;
use Palach\Omnidesk\UseCases\V1\StoreCompany\Response as StoreCompanyResponse;
use Palach\Omnidesk\UseCases\V1\UpdateCompany\Payload as UpdateCompanyPayload;
use Palach\Omnidesk\UseCases\V1\UpdateCompany\Response as UpdateCompanyResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class CompaniesClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/companies.json';

    private const string COMPANY_URL = '/api/companies/%s.json';

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
    public function update(int $companyId, UpdateCompanyPayload $payload): UpdateCompanyResponse
    {
        $url = sprintf(self::COMPANY_URL, $companyId);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, $payload->toArray());

        $company = $this->extract('company', $response);

        return new UpdateCompanyResponse(
            company: CompanyData::from($company),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function getCompany(FetchCompanyPayload $payload): FetchCompanyResponse
    {
        $url = sprintf(self::COMPANY_URL, $payload->companyId);

        $response = $this->transport->get($url);

        $company = $this->extract('company', $response);

        return new FetchCompanyResponse(
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

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function deleteCompany(int $companyId): DeleteCompanyResponse
    {
        $url = sprintf(self::COMPANY_URL, $companyId);

        $response = $this->transport->sendJson(Request::METHOD_DELETE, $url, []);

        $company = $this->extract('company', $response);

        return new DeleteCompanyResponse(
            company: CompanyData::from($company),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function blockCompany(int $companyId): BlockCompanyResponse
    {
        $url = sprintf(self::COMPANY_URL, $companyId);
        $url = str_replace('.json', '/block.json', $url);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, []);

        $company = $this->extract('company', $response);

        return new BlockCompanyResponse(
            company: CompanyData::from($company),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function disableCompany(int $companyId): DisabledCompanyResponse
    {
        $url = sprintf(self::COMPANY_URL, $companyId);
        $url = str_replace('.json', '/disable.json', $url);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, []);

        $company = $this->extract('company', $response);

        return new DisabledCompanyResponse(
            company: CompanyData::from($company),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function recoveryCompany(int $companyId): RecoveryCompanyResponse
    {
        $url = sprintf(self::COMPANY_URL, $companyId);
        $url = str_replace('.json', '/restore.json', $url);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, []);

        $company = $this->extract('company', $response);

        return new RecoveryCompanyResponse(
            company: CompanyData::from($company),
        );
    }
}
