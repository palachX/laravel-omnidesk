<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchIdeaCategoryList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\IdeaCategoryData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchIdeaCategoryList\Response as FetchIdeaCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchIdeaCategoryListResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full category data with default group' => [
            'data' => [
                'idea_categories' => [
                    [
                        'category_id' => 234,
                        'category_title' => 'Test category 1',
                        'category_default_group' => 44152,
                        'active' => true,
                    ],
                    [
                        'category_id' => 235,
                        'category_title' => 'Test category 2',
                        'category_default_group' => 43983,
                        'active' => false,
                    ],
                ],
                'total' => 10,
            ],

            'expected' => new FetchIdeaCategoryResponse(
                ideaCategories: new Collection([
                    new IdeaCategoryData(
                        categoryId: 234,
                        categoryTitle: 'Test category 1',
                        active: true,
                        categoryDefaultGroup: 44152,
                    ),
                    new IdeaCategoryData(
                        categoryId: 235,
                        categoryTitle: 'Test category 2',
                        active: false,
                        categoryDefaultGroup: 43983,
                    ),
                ]),
                total: 10,
            ),
        ];

        yield 'minimal category data without default group' => [
            'data' => [
                'idea_categories' => [
                    [
                        'category_id' => 236,
                        'category_title' => 'Test category 3',
                        'active' => true,
                    ],
                ],
                'total' => 1,
            ],

            'expected' => new FetchIdeaCategoryResponse(
                ideaCategories: new Collection([
                    new IdeaCategoryData(
                        categoryId: 236,
                        categoryTitle: 'Test category 3',
                        active: true,
                    ),
                ]),
                total: 1,
            ),
        ];

        yield 'empty categories list' => [
            'data' => [
                'idea_categories' => [],
                'total' => 0,
            ],

            'expected' => new FetchIdeaCategoryResponse(
                ideaCategories: new Collection,
                total: 0,
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchIdeaCategoryResponse $expected): void
    {
        $actual = FetchIdeaCategoryResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
