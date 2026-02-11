<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use LogicException;
use Palach\Omnidesk\DTO\CaseData;
use Palach\Omnidesk\DTO\MessageData;
use Palach\Omnidesk\DTO\OmnideskConfig;
use Palach\Omnidesk\UseCases\V1\FetchCaseList\Payload as FetchCaseListPayload;
use Palach\Omnidesk\UseCases\V1\FetchCaseList\Response as FetchCaseListResponse;
use Palach\Omnidesk\UseCases\V1\StoreCase\Payload as StoreCasePayload;
use Palach\Omnidesk\UseCases\V1\StoreCase\Response as StoreCaseResponse;
use Palach\Omnidesk\UseCases\V1\StoreMessage\Payload as StoreMessagePayload;
use Palach\Omnidesk\UseCases\V1\StoreMessage\Response as StoreMessageResponse;
use Palach\Omnidesk\UseCases\V1\UpdateMessage\Payload as UpdateMessagePayload;
use Palach\Omnidesk\UseCases\V1\UpdateMessage\Response as UpdateMessageResponse;
use Spatie\LaravelData\Optional;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\UnexpectedResponseException;

final readonly class HttpClient
{
    public const string API_URL_CASES = '/api/cases.json';

    public const string API_URL_STORE_MESSAGES = '/api/cases/%s/messages.json';

    public const string API_URL_UPDATE_MESSAGES = '/api/cases/%s/messages/%d.json';

    public function __construct(
        private OmnideskConfig $config
    ) {}

    private function getBaseHttp(): PendingRequest
    {
        return Http::withBasicAuth(
            $this->config->email,
            $this->config->apiKey
        )->baseUrl($this->config->host)
            ->withHeaders([
                'Accept' => 'application/json',
            ]);
    }

    /**
     * @param  array<mixed>  $bodyParams
     *
     * @throws RequestException
     * @throws ConnectionException
     */
    private function sendPostRequest(
        string $url,
        array $bodyParams = [],
        bool $isMultipart = false,
    ): mixed {
        $request = $this->getBaseHttp();

        if ($isMultipart) {
            return $request
                ->asMultipart()
                ->post($url, $bodyParams)
                ->throw()
                ->json();
        }

        return $request
            ->asJson()
            ->post($url, $bodyParams)
            ->throw()
            ->json();
    }

    /**
     * @param  array<mixed>  $query
     *
     * @throws ConnectionException
     * @throws RequestException
     */
    private function sendGetRequest(
        string $url,
        array $query,
    ): mixed {
        return $this->getBaseHttp()
            ->withQueryParameters($query)
            ->send(Request::METHOD_GET, $url)
            ->throw()
            ->json();
    }

    /**
     * @throws ConnectionException
     * @throws RequestException
     */
    public function storeCase(StoreCasePayload $payload): StoreCaseResponse
    {
        if ($payload->isAttachment()) {
            $data = $payload->toMultipart();
        } else {
            $data = $payload->toArray();
        }

        $response = $this->sendPostRequest(self::API_URL_CASES, $data, $payload->isAttachment());

        if (! is_array($response) || ! array_key_exists('case', $response)) {
            throw new UnexpectedResponseException('Case not found in response');
        }

        return new StoreCaseResponse(
            case: CaseData::from($response['case'])
        );
    }

    /**
     * @throws ConnectionException
     * @throws RequestException
     */
    public function fetchCaseList(FetchCaseListPayload $payload): FetchCaseListResponse
    {
        $response = $this->sendGetRequest(self::API_URL_CASES, $payload->toQuery());

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        /**
         * @var array<int, array{case: array<string, mixed>}> $casesRaw
         */
        $casesRaw = array_values($response);

        $cases = collect($casesRaw)
            ->map(function (array $item) {
                return CaseData::from($item['case']);
            });

        return new FetchCaseListResponse(
            cases: $cases,
            total: $total
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function storeMessage(StoreMessagePayload $payload): StoreMessageResponse
    {
        $caseNumberOrId = $this->getCaseNumberOrId($payload);
        $url = sprintf(self::API_URL_STORE_MESSAGES, $caseNumberOrId);

        if ($payload->isAttachment()) {
            $data = $payload->toMultipart();
        } else {
            $data = $payload->toArray();
        }

        $response = $this->sendPostRequest($url, $data, $payload->isAttachment());

        if (! is_array($response) || ! array_key_exists('message', $response)) {
            throw new UnexpectedResponseException('Message not found in response');
        }

        return new StoreMessageResponse(
            message: MessageData::from($response['message'])
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function updateMessage(UpdateMessagePayload $payload): UpdateMessageResponse
    {
        $caseNumberOrId = $this->getCaseNumberOrId($payload);

        $url = sprintf(self::API_URL_UPDATE_MESSAGES, $caseNumberOrId, $payload->message->messageId);
        $response = $this->sendPostRequest($url, $payload->toArray());

        if (! is_array($response) || ! array_key_exists('message', $response)) {
            throw new UnexpectedResponseException('Message not found in response');
        }

        return new UpdateMessageResponse(
            message: MessageData::from($response['message'])
        );
    }

    private function getCaseNumberOrId(StoreMessagePayload|UpdateMessagePayload $payload): int|string
    {
        return match (true) {
            $payload->message->caseId instanceof Optional && $payload->message->caseNumber instanceof Optional => throw new InvalidArgumentException('Not set caseId or CaseNumber'),
            ! ($payload->message->caseId instanceof Optional) => $payload->message->caseId,
            ! ($payload->message->caseNumber instanceof Optional) => $payload->message->caseNumber,
            default => throw new LogicException('Both caseId and caseNumber are set'),
        };
    }
}
