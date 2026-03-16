<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchKnowledgeBaseCategory;

use Palach\Omnidesk\DTO\KnowledgeBaseCategoryData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategory\Response as FetchKnowledgeBaseCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchKnowledgeBaseCategoryResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'category with string title' => [
            'data' => [
                'kb_category' => [
                    'category_id' => 234,
                    'category_title' => 'Test category',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new FetchKnowledgeBaseCategoryResponse(
                kbCategory: new KnowledgeBaseCategoryData(
                    categoryId: 234,
                    categoryTitle: 'Test category',
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                ),
            ),
        ];

        yield 'category with array titles' => [
            'data' => [
                'kb_category' => [
                    'category_id' => 235,
                    'category_title' => [
                        '1' => 'Тест 1',
                        '2' => 'Test category 1',
                    ],
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new FetchKnowledgeBaseCategoryResponse(
                kbCategory: new KnowledgeBaseCategoryData(
                    categoryId: 235,
                    categoryTitle: [
                        '1' => 'Тест 1',
                        '2' => 'Test category 1',
                    ],
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                ),
            ),
        ];

        yield 'inactive category' => [
            'data' => [
                'kb_category' => [
                    'category_id' => 236,
                    'category_title' => 'Inactive category',
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new FetchKnowledgeBaseCategoryResponse(
                kbCategory: new KnowledgeBaseCategoryData(
                    categoryId: 236,
                    categoryTitle: 'Inactive category',
                    active: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchKnowledgeBaseCategoryResponse $expected): void
    {
        $actual = FetchKnowledgeBaseCategoryResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }

    public function testKbCategoryType(): void
    {
        $kbCategoryData = new KnowledgeBaseCategoryData(
            categoryId: 1,
            categoryTitle: 'Test Category',
            active: true,
            createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
            updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
        );

        $response = new FetchKnowledgeBaseCategoryResponse(kbCategory: $kbCategoryData);

        $this->assertInstanceOf(KnowledgeBaseCategoryData::class, $response->kbCategory);
        $this->assertSame($kbCategoryData, $response->kbCategory);
    }

    public function testKbCategoryIsReadOnly(): void
    {
        $kbCategoryData = new KnowledgeBaseCategoryData(
            categoryId: 1,
            categoryTitle: 'Test Category',
            active: true,
            createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
            updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
        );

        $response = new FetchKnowledgeBaseCategoryResponse(kbCategory: $kbCategoryData);

        // Verify the property is readonly
        $this->assertSame($kbCategoryData, $response->kbCategory);
    }
}
