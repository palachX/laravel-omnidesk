<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\CaseData;
use Palach\Omnidesk\DTO\ChangelogData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\DeleteCase\BulkPayload as DeleteCaseBulkPayload;
use Palach\Omnidesk\UseCases\V1\DeleteCase\BulkResponse as DeleteCaseBulkResponse;
use Palach\Omnidesk\UseCases\V1\DeleteCase\Payload as DeleteCasePayload;
use Palach\Omnidesk\UseCases\V1\DeleteCase\Response as DeleteCaseResponse;
use Palach\Omnidesk\UseCases\V1\DeleteIdeaOfficialResponse\Payload as DeleteIdeaOfficialResponsePayload;
use Palach\Omnidesk\UseCases\V1\FetchCase\Payload as FetchCasePayload;
use Palach\Omnidesk\UseCases\V1\FetchCase\Response as FetchCaseResponse;
use Palach\Omnidesk\UseCases\V1\FetchCaseChangelog\Payload as FetchCaseChangelogPayload;
use Palach\Omnidesk\UseCases\V1\FetchCaseChangelog\Response as FetchCaseChangelogResponse;
use Palach\Omnidesk\UseCases\V1\FetchCaseList\Payload as FetchCaseListPayload;
use Palach\Omnidesk\UseCases\V1\FetchCaseList\Response as FetchCaseListResponse;
use Palach\Omnidesk\UseCases\V1\RateCase\Payload as RateCasePayload;
use Palach\Omnidesk\UseCases\V1\RateCase\Response as RateCaseResponse;
use Palach\Omnidesk\UseCases\V1\RestoreCase\BulkPayload as RestoreCaseBulkPayload;
use Palach\Omnidesk\UseCases\V1\RestoreCase\BulkResponse as RestoreCaseBulkResponse;
use Palach\Omnidesk\UseCases\V1\RestoreCase\Payload as RestoreCasePayload;
use Palach\Omnidesk\UseCases\V1\RestoreCase\Response as RestoreCaseResponse;
use Palach\Omnidesk\UseCases\V1\SpamCase\BulkPayload as SpamCaseBulkPayload;
use Palach\Omnidesk\UseCases\V1\SpamCase\BulkResponse as SpamCaseBulkResponse;
use Palach\Omnidesk\UseCases\V1\SpamCase\Payload as SpamCasePayload;
use Palach\Omnidesk\UseCases\V1\SpamCase\Response as SpamCaseResponse;
use Palach\Omnidesk\UseCases\V1\StoreCase\Payload as StoreCasePayload;
use Palach\Omnidesk\UseCases\V1\StoreCase\Response as StoreCaseResponse;
use Palach\Omnidesk\UseCases\V1\TrashCase\BulkPayload as TrashCaseBulkPayload;
use Palach\Omnidesk\UseCases\V1\TrashCase\BulkResponse as TrashCaseBulkResponse;
use Palach\Omnidesk\UseCases\V1\TrashCase\Payload as TrashCasePayload;
use Palach\Omnidesk\UseCases\V1\TrashCase\Response as TrashCaseResponse;
use Palach\Omnidesk\UseCases\V1\UpdateIdea\Payload as UpdateIdeaPayload;
use Palach\Omnidesk\UseCases\V1\UpdateIdea\Response as UpdateIdeaResponse;
use Palach\Omnidesk\UseCases\V1\UpdateIdeaOfficialResponse\Payload as UpdateIdeaOfficialResponsePayload;
use Palach\Omnidesk\UseCases\V1\UpdateIdeaOfficialResponse\Response as UpdateIdeaOfficialResponseResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class CasesClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/cases.json';

    private const string RATE_URL = '/api/cases/%s/rate.json';

    private const string TRASH_URL = '/api/cases/%s/trash.json';

    private const string RESTORE_URL = '/api/cases/%s/restore.json';

    private const string DELETE_URL = '/api/cases/%s.json';

    private const string SPAM_URL = '/api/cases/%s/spam.json';

    private const string CASE_URL = '/api/cases/%s.json';

    private const string CHANGELOG_URL = '/api/cases/%s/changelog.json';

    private const string IDEA_URL = '/api/cases/%s/idea.json';

    private const string IDEA_OFFICIAL_RESPONSE_URL = '/api/cases/%s/idea_official_response.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function getCase(FetchCasePayload $payload): FetchCaseResponse
    {
        $url = sprintf(self::CASE_URL, $payload->caseId);

        $response = $this->transport->get($url);

        $case = $this->extract('case', $response);

        return new FetchCaseResponse(
            case: CaseData::from($case),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function getChangelog(FetchCaseChangelogPayload $payload): FetchCaseChangelogResponse
    {
        $url = sprintf(self::CHANGELOG_URL, $payload->caseId);

        $response = $this->transport->get($url, $payload->toQuery());

        $changelog = $this->extract('changelog', $response);

        $changelogItems = collect($changelog)
            ->map(fn ($item) => ChangelogData::from($item));

        return new FetchCaseChangelogResponse(
            changelog: $changelogItems,
        );
    }

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

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function trashCase(TrashCasePayload $payload): TrashCaseResponse
    {
        $url = sprintf(self::TRASH_URL, $payload->caseId);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url);

        $case = $this->extract('case', $response);

        return new TrashCaseResponse(
            case: CaseData::from($case),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function trashBulk(TrashCaseBulkPayload $payload): TrashCaseBulkResponse
    {
        $url = sprintf(self::TRASH_URL, implode(',', $payload->caseIds));

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url);

        /** @var int[] $caseSuccessId */
        $caseSuccessId = $this->extract('case_success_id', $response);

        return new TrashCaseBulkResponse(
            caseSuccessId: $caseSuccessId,
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function restoreCase(RestoreCasePayload $payload): RestoreCaseResponse
    {
        $url = sprintf(self::RESTORE_URL, $payload->caseId);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url);

        $case = $this->extract('case', $response);

        return new RestoreCaseResponse(
            case: CaseData::from($case),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function restoreBulk(RestoreCaseBulkPayload $payload): RestoreCaseBulkResponse
    {
        $url = sprintf(self::RESTORE_URL, implode(',', $payload->caseIds));

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url);

        /** @var int[] $caseSuccessId */
        $caseSuccessId = $this->extract('case_success_id', $response);

        return new RestoreCaseBulkResponse(
            caseSuccessId: $caseSuccessId,
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function deleteCase(DeleteCasePayload $payload): DeleteCaseResponse
    {
        $url = sprintf(self::DELETE_URL, $payload->caseId);

        $response = $this->transport->sendJson(Request::METHOD_DELETE, $url);

        $case = $this->extract('case', $response);

        return new DeleteCaseResponse(
            case: CaseData::from($case),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function deleteBulk(DeleteCaseBulkPayload $payload): DeleteCaseBulkResponse
    {
        $url = sprintf(self::DELETE_URL, implode(',', $payload->caseIds));

        $response = $this->transport->sendJson(Request::METHOD_DELETE, $url);

        /** @var int[] $caseSuccessId */
        $caseSuccessId = $this->extract('case_success_id', $response);

        return new DeleteCaseBulkResponse(
            caseSuccessId: $caseSuccessId,
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function spamCase(SpamCasePayload $payload): SpamCaseResponse
    {
        $url = sprintf(self::SPAM_URL, $payload->caseId);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url);

        $case = $this->extract('case', $response);

        return new SpamCaseResponse(
            case: CaseData::from($case),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function spamBulk(SpamCaseBulkPayload $payload): SpamCaseBulkResponse
    {
        $url = sprintf(self::SPAM_URL, implode(',', $payload->caseIds));

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url);

        /** @var int[] $caseSuccessId */
        $caseSuccessId = $this->extract('case_success_id', $response);

        return new SpamCaseBulkResponse(
            caseSuccessId: $caseSuccessId,
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function updateIdea(UpdateIdeaPayload $payload): UpdateIdeaResponse
    {
        $url = sprintf(self::IDEA_URL, $payload->caseId);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, $payload->toArray());

        $case = $this->extract('case', $response);

        return new UpdateIdeaResponse(
            case: CaseData::from($case),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function updateIdeaOfficialResponse(UpdateIdeaOfficialResponsePayload $payload): UpdateIdeaOfficialResponseResponse
    {
        $url = sprintf(self::IDEA_OFFICIAL_RESPONSE_URL, $payload->caseId);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, $payload->toArray());

        $case = $this->extract('case', $response);

        return new UpdateIdeaOfficialResponseResponse(
            case: CaseData::from($case),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function deleteIdeaOfficialResponse(DeleteIdeaOfficialResponsePayload $payload): void
    {
        $url = sprintf(self::IDEA_OFFICIAL_RESPONSE_URL, $payload->caseId);

        $this->transport->sendJson(Request::METHOD_DELETE, $url);
    }
}
