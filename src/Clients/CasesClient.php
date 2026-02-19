<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\CaseData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\FetchCaseList\Payload as FetchCaseListPayload;
use Palach\Omnidesk\UseCases\V1\FetchCaseList\Response as FetchCaseListResponse;
use Palach\Omnidesk\UseCases\V1\RateCase\Payload as RateCasePayload;
use Palach\Omnidesk\UseCases\V1\RateCase\Response as RateCaseResponse;
use Palach\Omnidesk\UseCases\V1\StoreCase\Payload as StoreCasePayload;
use Palach\Omnidesk\UseCases\V1\StoreCase\Response as StoreCaseResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class CasesClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/cases.json';

    private const string RATE_URL = '/api/cases/%s/rate.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function store(StoreCasePayload $payload): StoreCaseResponse
    {
        $response = $payload->isAttachment()
            ? $this->transport->sendMultipart(Request::METHOD_POST, self::API_URL, $payload->toMultipart())
            : $this->transport->sendJson(Request::METHOD_POST, self::API_URL, $payload->toArray());

        $case = $this->extract('case', $response);

        return new StoreCaseResponse(
            case: CaseData::from($case),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function rateCase(RateCasePayload $payload): RateCaseResponse
    {
        $url = sprintf(self::RATE_URL, $payload->caseId);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, $payload->toArray());

        $case = $this->extract('case', $response);

        return new RateCaseResponse(
            case: CaseData::from($case),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchList(FetchCaseListPayload $payload): FetchCaseListResponse
    {
        $response = $this->transport->get(self::API_URL, $payload->toQuery());

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        $cases = collect($response)
            ->map(fn ($item) => CaseData::from($item['case']));

        return new FetchCaseListResponse(
            cases: $cases,
            total: $total,
        );
    }
}
