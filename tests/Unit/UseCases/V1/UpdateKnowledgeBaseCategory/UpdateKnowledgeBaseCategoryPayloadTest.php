<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateKnowledgeBaseCategory;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseCategory\KnowledgeBaseCategoryUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseCategory\Payload as UpdateKnowledgeBaseCategoryPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateKnowledgeBaseCategoryPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'string title' => [
            'data' => [
                'kb_category' => [
                    'category_title' => 'Test Category Updated',
                ],
            ],

            'expected' => new UpdateKnowledgeBaseCategoryPayload(
                kbCategory: new KnowledgeBaseCategoryUpdateData(
                    categoryTitle: 'Test Category Updated',
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

            'expected' => new UpdateKnowledgeBaseCategoryPayload(
                kbCategory: new KnowledgeBaseCategoryUpdateData(
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
                    'category_title' => 'Minimal Update',
                ],
            ],

            'expected' => new UpdateKnowledgeBaseCategoryPayload(
                kbCategory: new KnowledgeBaseCategoryUpdateData(
                    categoryTitle: 'Minimal Update',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testValidateAndCreate(array $data, UpdateKnowledgeBaseCategoryPayload $expected): void
    {
        $actual = UpdateKnowledgeBaseCategoryPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
