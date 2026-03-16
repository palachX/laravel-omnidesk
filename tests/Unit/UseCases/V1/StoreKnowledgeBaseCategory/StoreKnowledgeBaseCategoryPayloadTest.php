<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreKnowledgeBaseCategory;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseCategory\KnowledgeBaseCategoryStoreData;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseCategory\Payload as StoreKnowledgeBaseCategoryPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreKnowledgeBaseCategoryPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'string title' => [
            'data' => [
                'kb_category' => [
                    'category_title' => 'Test Category Stored',
                ],
            ],

            'expected' => new StoreKnowledgeBaseCategoryPayload(
                kbCategory: new KnowledgeBaseCategoryStoreData(
                    categoryTitle: 'Test Category Stored',
                )
            ),
        ];

        yield 'multilingual title' => [
            'data' => [
                'kb_category' => [
                    'category_title' => [
                        '1' => 'Тестовая категория',
                        '2' => 'Test Category',
                    ],
                ],
            ],

            'expected' => new StoreKnowledgeBaseCategoryPayload(
                kbCategory: new KnowledgeBaseCategoryStoreData(
                    categoryTitle: [
                        '1' => 'Тестовая категория',
                        '2' => 'Test Category',
                    ],
                )
            ),
        ];

        yield 'minimal data' => [
            'data' => [
                'kb_category' => [
                    'category_title' => 'Minimal Store',
                ],
            ],

            'expected' => new StoreKnowledgeBaseCategoryPayload(
                kbCategory: new KnowledgeBaseCategoryStoreData(
                    categoryTitle: 'Minimal Store',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, StoreKnowledgeBaseCategoryPayload $expected): void
    {
        $payload = StoreKnowledgeBaseCategoryPayload::validateAndCreate($data);

        $this->assertEquals($expected, $payload);
    }
}
