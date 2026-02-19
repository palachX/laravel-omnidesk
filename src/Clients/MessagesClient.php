<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\MessageData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\StoreMessage\Payload as StoreMessagePayload;
use Palach\Omnidesk\UseCases\V1\StoreMessage\Response as StoreMessageResponse;
use Palach\Omnidesk\UseCases\V1\UpdateMessage\Payload as UpdateMessagePayload;
use Palach\Omnidesk\UseCases\V1\UpdateMessage\Response as UpdateMessageResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class MessagesClient
{
    use ExtractsResponseData;

    private const string STORE_URL = '/api/cases/%s/messages.json';

    private const string UPDATE_URL = '/api/cases/%s/messages/%d.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function store(StoreMessagePayload $payload): StoreMessageResponse
    {
        $url = sprintf(self::STORE_URL, $payload->caseId);

        $response = $payload->isAttachment()
            ? $this->transport->sendMultipart(Request::METHOD_POST, $url, $payload->toMultipart())
            : $this->transport->sendJson(Request::METHOD_POST, $url, $payload->toArray());

        $message = $this->extract('message', $response);

        return new StoreMessageResponse(
            message: MessageData::from($message),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function update(UpdateMessagePayload $payload): UpdateMessageResponse
    {
        $url = sprintf(
            self::UPDATE_URL,
            $payload->caseId,
            $payload->messageId
        );

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, $payload->toArray());

        $message = $this->extract('message', $response);

        return new UpdateMessageResponse(
            message: MessageData::from($message),
        );
    }
}
