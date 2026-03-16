<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchKnowledgeBaseCategoryList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\KnowledgeBaseCategoryData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategoryList\Response as FetchKnowledgeBaseCategoryListResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchKnowledgeBaseCategoryListResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data with single language' => [
            'data' => [
                'kb_categories' => [
                    [
                        'category_id' => 234,
                        'category_title' => 'Test category 1',
                        'active' => true,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ],
                    [
                        'category_id' => 235,
                        'category_title' => 'Test category 2',
                        'active' => false,
                        'created_at' => 'Mon, 15 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 13 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total' => 10,
            ],

            'expected' => new FetchKnowledgeBaseCategoryListResponse(
                kbCategories: new Collection([
                    new KnowledgeBaseCategoryData(
                        categoryId: 234,
                        categoryTitle: 'Test category 1',
                        active: true,
                        createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                        updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200'
                    ),
                    new KnowledgeBaseCategoryData(
                        categoryId: 235,
                        categoryTitle: 'Test category 2',
                        active: false,
                        createdAt: 'Mon, 15 May 2014 00:15:17 +0300',
                        updatedAt: 'Tue, 13 Dec 2014 10:55:23 +0200'
                    ),
                ]),
                total: 10
            ),
        ];

        yield 'full data with multiple languages' => [
            'data' => [
                'kb_categories' => [
                    [
                        'category_id' => 234,
                        'category_title' => [
                            '1' => 'Тест 1',
                            '2' => 'Test category 1',
                        ],
                        'active' => true,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total' => 5,
            ],

            'expected' => new FetchKnowledgeBaseCategoryListResponse(
                kbCategories: new Collection([
                    new KnowledgeBaseCategoryData(
                        categoryId: 234,
                        categoryTitle: [
                            '1' => 'Тест 1',
                            '2' => 'Test category 1',
                        ],
                        active: true,
                        createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                        updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200'
                    ),
                ]),
                total: 5
            ),
        ];

        yield 'empty categories' => [
            'data' => [
                'kb_categories' => [],
                'total' => 0,
            ],

            'expected' => new FetchKnowledgeBaseCategoryListResponse(
                kbCategories: new Collection([]),
                total: 0
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchKnowledgeBaseCategoryListResponse $expected): void
    {
        $actual = FetchKnowledgeBaseCategoryListResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
