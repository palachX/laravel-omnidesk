<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\FilterData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchFilterList\Response as FetchFilterListResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchFilterListTest extends AbstractTestCase
{
    private const string API_URL_FILTERS = '/api/filters.json';

    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'response' => [
                '0' => [
                    'filter' => [
                        'filter_id' => 235,
                        'filter_name' => 'open',
                        'isSelected' => true,
                        'isCustom' => false,
                    ],
                ],
                '1' => [
                    'filter' => [
                        'filter_id' => 234,
                        'filter_name' => 'Test filter',
                        'isSelected' => false,
                        'isCustom' => true,
                    ],
                ],
                'total_count' => 10,
            ],
        ];
        yield 'no pagination' => [
            'response' => [
                '0' => [
                    'filter' => [
                        'filter_id' => 0,
                        'filter_name' => 'open',
                        'isSelected' => true,
                        'isCustom' => false,
                    ],
                ],
                'total_count' => 1,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $response): void
    {
        $url = self::API_URL_FILTERS;
        $fullUrl = $this->host.$url;

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->filters()->fetchList();

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $totalCount = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        /**
         * @var array<int, array{filter: array<string, mixed>}> $filtersRaw
         */
        $filtersRaw = array_values($response);

        $filters = collect($filtersRaw)
            ->map(function (array $item) {
                return FilterData::from($item['filter']);
            });

        $this->assertEquals(new FetchFilterListResponse(
            filters: $filters,
            totalCount: $totalCount
        ), $list);
    }
}
