<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\LabelData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchLabelList\Payload as FetchLabelListPayload;
use Palach\Omnidesk\UseCases\V1\FetchLabelList\Response as FetchLabelListResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchLabelListTest extends AbstractTestCase
{
    private const string API_URL_LABELS = '/api/labels.json';

    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'payload' => [
                'page' => 1,
                'limit' => 2,
            ],
            'response' => [
                [
                    'label' => [
                        'label_id' => 200,
                        'label_title' => 'Test title',
                    ],
                ],
                [
                    'label' => [
                        'label_id' => 210,
                        'label_title' => 'Test title 2',
                    ],
                ],
                'total_count' => 2,
            ],
        ];
        yield 'default values' => [
            'payload' => [
                'page' => 1,
                'limit' => 1,
            ],
            'response' => [
                [
                    'label' => [
                        'label_id' => 200,
                        'label_title' => 'Test title',
                    ],
                ],
                'total_count' => 1,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = FetchLabelListPayload::from($payload);

        $url = self::API_URL_LABELS;
        $query = http_build_query($payload->toQuery());
        $fullUrl = $this->host.$url.'?'.$query;

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->labels()->fetchList(FetchLabelListPayload::from($payload));

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        /**
         * @var array<int, array{label: array<string, mixed>}> $labelsRaw
         */
        $labelsRaw = array_values($response);

        $labels = collect($labelsRaw)
            ->map(function (array $item) {
                return LabelData::from($item['label']);
            });

        $this->assertEquals(new FetchLabelListResponse(
            labels: $labels,
            total: $total
        ), $list);
    }
}
