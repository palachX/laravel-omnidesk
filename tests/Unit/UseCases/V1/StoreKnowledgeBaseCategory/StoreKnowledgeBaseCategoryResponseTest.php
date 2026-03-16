<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreKnowledgeBaseCategory;

use Palach\Omnidesk\DTO\KnowledgeBaseCategoryData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseCategory\Response as StoreKnowledgeBaseCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreKnowledgeBaseCategoryResponseTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'kb category response title single language' => [
            'data' => [
                'kb_category' => [
                    'category_id' => 234,
                    'category_title' => 'Test category',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
            'expected' => new StoreKnowledgeBaseCategoryResponse(
                kbCategory: new KnowledgeBaseCategoryData(
                    categoryId: 234,
                    categoryTitle: 'Test category',
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                )
            ),
        ];

        yield 'kb category response title multiple languages' => [
            'data' => [
                'kb_category' => [
                    'category_id' => 234,
                    'category_title' => [
                        '1' => 'Название категории',
                        '2' => 'Category name',
                    ],
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
            'expected' => new StoreKnowledgeBaseCategoryResponse(
                kbCategory: new KnowledgeBaseCategoryData(
                    categoryId: 234,
                    categoryTitle: [
                        '1' => 'Название категории',
                        '2' => 'Category name',
                    ],
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                )
            ),
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testFromArray(array $data, StoreKnowledgeBaseCategoryResponse $expected): void
    {
        $response = StoreKnowledgeBaseCategoryResponse::from($data);

        $this->assertEquals($expected, $response);
    }
}
