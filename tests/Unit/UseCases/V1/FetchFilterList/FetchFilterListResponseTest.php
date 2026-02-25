<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchFilterList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\FilterData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchFilterList\Response as FetchFilterListResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchFilterListResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data with multiple filters' => [
            'data' => [
                'filters' => [
                    [
                        'filter_id' => 1,
                        'filter_name' => 'All Cases',
                        'is_selected' => true,
                        'is_custom' => false,
                    ],
                    [
                        'filter_id' => 2,
                        'filter_name' => 'My Cases',
                        'is_selected' => false,
                        'is_custom' => false,
                    ],
                    [
                        'filter_id' => 3,
                        'filter_name' => 'Custom Filter',
                        'is_selected' => false,
                        'is_custom' => true,
                    ],
                ],
                'total_count' => 3,
            ],

            'expected' => new FetchFilterListResponse(
                filters: new Collection([
                    new FilterData(
                        filterId: 1,
                        filterName: 'All Cases',
                        isSelected: true,
                        isCustom: false,
                    ),
                    new FilterData(
                        filterId: 2,
                        filterName: 'My Cases',
                        isSelected: false,
                        isCustom: false,
                    ),
                    new FilterData(
                        filterId: 3,
                        filterName: 'Custom Filter',
                        isSelected: false,
                        isCustom: true,
                    ),
                ]),
                totalCount: 3
            ),
        ];

        yield 'empty filters list' => [
            'data' => [
                'filters' => [],
                'total_count' => 0,
            ],

            'expected' => new FetchFilterListResponse(
                filters: new Collection([]),
                totalCount: 0
            ),
        ];

        yield 'single filter' => [
            'data' => [
                'filters' => [
                    [
                        'filter_id' => 10,
                        'filter_name' => 'Urgent Cases',
                        'is_selected' => true,
                        'is_custom' => true,
                    ],
                ],
                'total_count' => 1,
            ],

            'expected' => new FetchFilterListResponse(
                filters: new Collection([
                    new FilterData(
                        filterId: 10,
                        filterName: 'Urgent Cases',
                        isSelected: true,
                        isCustom: true,
                    ),
                ]),
                totalCount: 1
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchFilterListResponse $expected): void
    {
        $actual = FetchFilterListResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }

    public function testFiltersCollectionType(): void
    {
        $response = new FetchFilterListResponse(
            filters: new Collection([
                new FilterData(
                    filterId: 1,
                    filterName: 'Test Filter',
                    isSelected: false,
                    isCustom: false,
                ),
            ]),
            totalCount: 1
        );

        $this->assertInstanceOf(Collection::class, $response->filters);
        $this->assertCount(1, $response->filters);
        $this->assertInstanceOf(FilterData::class, $response->filters->first());
    }

    public function testTotalCountType(): void
    {
        $response = new FetchFilterListResponse(
            filters: new Collection([]),
            totalCount: 5
        );

        $this->assertIsInt($response->totalCount);
        $this->assertSame(5, $response->totalCount);
    }
}
