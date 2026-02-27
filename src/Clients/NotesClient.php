<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\DTO\MessageData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\DeleteNote\Payload as DeleteNotePayload;
use Palach\Omnidesk\UseCases\V1\StoreNote\Payload as StoreNotePayload;
use Palach\Omnidesk\UseCases\V1\StoreNote\Response as StoreNoteResponse;
use Palach\Omnidesk\UseCases\V1\UpdateNote\Payload as UpdateNotePayload;
use Palach\Omnidesk\UseCases\V1\UpdateNote\Response as UpdateNoteResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class NotesClient
{
    use ExtractsResponseData;

    private const string STORE_URL = '/api/cases/%s/note.json';

    private const string UPDATE_URL = '/api/cases/%s/note/%d.json';

    private const string DELETE_URL = '/api/cases/%s/note/%d.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function store(StoreNotePayload $payload): StoreNoteResponse
    {
        $url = sprintf(self::STORE_URL, $payload->caseId);

        $response = $payload->isAttachment()
            ? $this->transport->sendMultipart(Request::METHOD_POST, $url, $payload->toMultipart())
            : $this->transport->sendJson(Request::METHOD_POST, $url, $payload->toArray());

        $message = $this->extract('message', $response);

        return new StoreNoteResponse(
            message: MessageData::from($message),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function update(UpdateNotePayload $payload): UpdateNoteResponse
    {
        $url = sprintf(
            self::UPDATE_URL,
            $payload->caseId,
            $payload->messageId
        );

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, $payload->toArray());

        $message = $this->extract('message', $response);

        return new UpdateNoteResponse(
            message: MessageData::from($message),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function deleteNote(DeleteNotePayload $payload): void
    {
        $url = sprintf(self::DELETE_URL, $payload->caseId, $payload->messageId);

        $this->transport->sendJson(Request::METHOD_DELETE, $url);
    }
}
