<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreKnowledgeBaseSection;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseSection\KnowledgeBaseSectionStoreData;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseSection\Payload as StoreKnowledgeBaseSectionPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreKnowledgeBaseSectionPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'string title and description' => [
            'data' => [
                'kb_section' => [
                    'section_title' => 'Test Section Stored',
                    'section_description' => 'Test section description',
                    'category_id' => '1',
                ],
            ],

            'expected' => new StoreKnowledgeBaseSectionPayload(
                kbSection: new KnowledgeBaseSectionStoreData(
                    sectionTitle: 'Test Section Stored',
                    categoryId: '1',
                    sectionDescription: 'Test section description',
                )
            ),
        ];

        yield 'multilingual title and description' => [
            'data' => [
                'kb_section' => [
                    'section_title' => [
                        '1' => 'Тестовый раздел',
                        '2' => 'Test Section',
                    ],
                    'section_description' => [
                        '1' => 'Тестовое описание раздела',
                        '2' => 'Test section description',
                    ],
                    'category_id' => '1',
                ],
            ],

            'expected' => new StoreKnowledgeBaseSectionPayload(
                kbSection: new KnowledgeBaseSectionStoreData(
                    sectionTitle: [
                        '1' => 'Тестовый раздел',
                        '2' => 'Test Section',
                    ],
                    categoryId: '1',
                    sectionDescription: [
                        '1' => 'Тестовое описание раздела',
                        '2' => 'Test section description',
                    ],
                )
            ),
        ];

        yield 'minimal data' => [
            'data' => [
                'kb_section' => [
                    'section_title' => 'Minimal Store',
                    'category_id' => '1',
                ],
            ],

            'expected' => new StoreKnowledgeBaseSectionPayload(
                kbSection: new KnowledgeBaseSectionStoreData(
                    sectionTitle: 'Minimal Store',
                    categoryId: '1',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, StoreKnowledgeBaseSectionPayload $expected): void
    {
        $payload = StoreKnowledgeBaseSectionPayload::validateAndCreate($data);

        $this->assertEquals($expected, $payload);
    }
}
