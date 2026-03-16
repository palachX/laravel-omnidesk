<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateKnowledgeBaseCategory;

use Palach\Omnidesk\DTO\KnowledgeBaseCategoryData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseCategory\Response as UpdateKnowledgeBaseCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateKnowledgeBaseCategoryResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'string title' => [
            'data' => [
                'kb_category' => [
                    'category_id' => 234,
                    'category_title' => 'Test category 2',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new UpdateKnowledgeBaseCategoryResponse(
                kbCategory: new KnowledgeBaseCategoryData(
                    categoryId: 234,
                    categoryTitle: 'Test category 2',
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                )
            ),
        ];

        yield 'multilingual title' => [
            'data' => [
                'kb_category' => [
                    'category_id' => 235,
                    'category_title' => [
                        '1' => 'Тест 2',
                        '2' => 'Test category 2',
                    ],
                    'active' => true,
                    'created_at' => 'Wed, 15 Jun 2023 14:30:00 +0300',
                    'updated_at' => 'Thu, 25 Dec 2014 15:30:00 +0200',
                ],
            ],

            'expected' => new UpdateKnowledgeBaseCategoryResponse(
                kbCategory: new KnowledgeBaseCategoryData(
                    categoryId: 235,
                    categoryTitle: [
                        '1' => 'Тест 2',
                        '2' => 'Test category 2',
                    ],
                    active: true,
                    createdAt: 'Wed, 15 Jun 2023 14:30:00 +0300',
                    updatedAt: 'Thu, 25 Dec 2014 15:30:00 +0200',
                )
            ),
        ];

        yield 'inactive category' => [
            'data' => [
                'kb_category' => [
                    'category_id' => 236,
                    'category_title' => 'Updated Category Name',
                    'active' => false,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Fri, 26 Dec 2014 11:20:00 +0200',
                ],
            ],

            'expected' => new UpdateKnowledgeBaseCategoryResponse(
                kbCategory: new KnowledgeBaseCategoryData(
                    categoryId: 236,
                    categoryTitle: 'Updated Category Name',
                    active: false,
                    createdAt: 'Thu, 20 Jul 2023 09:15:00 +0300',
                    updatedAt: 'Fri, 26 Dec 2014 11:20:00 +0200',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testValidateAndCreate(array $data, UpdateKnowledgeBaseCategoryResponse $expected): void
    {
        $actual = UpdateKnowledgeBaseCategoryResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
