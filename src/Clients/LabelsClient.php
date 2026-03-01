<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\DeleteLabel\Payload as DeleteLabelPayload;
use Palach\Omnidesk\UseCases\V1\FetchLabelList\Payload as FetchLabelListPayload;
use Palach\Omnidesk\UseCases\V1\FetchLabelList\Response as FetchLabelListResponse;
use Palach\Omnidesk\UseCases\V1\StoreLabel\Payload as StoreLabelPayload;
use Palach\Omnidesk\UseCases\V1\StoreLabel\Response as StoreLabelResponse;
use Palach\Omnidesk\UseCases\V1\UpdateLabel\Payload as UpdateLabelPayload;
use Palach\Omnidesk\UseCases\V1\UpdateLabel\Response as UpdateLabelResponse;
use Symfony\Component\HttpFoundation\Request;

final readonly class LabelsClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/labels.json';

    private const string LABEL_URL = '/api/labels/%s.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function store(StoreLabelPayload $payload): StoreLabelResponse
    {
        $response = $this->transport->sendJson(
            Request::METHOD_POST,
            self::API_URL,
            $payload->toArray(),
        );

        return StoreLabelResponse::from($response);
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchList(FetchLabelListPayload $payload): FetchLabelListResponse
    {
        $response = $this->transport->get(self::API_URL, $payload->toQuery());

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        $labels = collect($response)
            ->map(fn ($item) => \Palach\Omnidesk\DTO\LabelData::from($item['label']));

        return new FetchLabelListResponse(
            labels: $labels,
            total: $total,
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function updateLabel(UpdateLabelPayload $payload): UpdateLabelResponse
    {
        $url = sprintf(self::LABEL_URL, $payload->labelId);

        $response = $this->transport->sendJson(Request::METHOD_PUT, $url, $payload->toArray());

        $label = $this->extract('label', $response);

        return new UpdateLabelResponse(
            label: \Palach\Omnidesk\DTO\LabelData::from($label),
        );
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function deleteLabel(DeleteLabelPayload $payload): void
    {
        $url = sprintf(self::LABEL_URL, $payload->id);

        $this->transport->sendJson(Request::METHOD_DELETE, $url);
    }
}
