<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateKnowledgeBaseSection;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseSection\KnowledgeBaseSectionUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseSection\Payload as UpdateKnowledgeBaseSectionPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateKnowledgeBaseSectionPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'string title and description' => [
            'data' => [
                'kb_section' => [
                    'section_title' => 'Test Section Updated',
                    'section_description' => 'Test section description updated',
                    'category_id' => 2,
                ],
            ],

            'expected' => new UpdateKnowledgeBaseSectionPayload(
                kbSection: new KnowledgeBaseSectionUpdateData(
                    sectionTitle: 'Test Section Updated',
                    sectionDescription: 'Test section description updated',
                    categoryId: 2,
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
                    'category_id' => 3,
                ],
            ],

            'expected' => new UpdateKnowledgeBaseSectionPayload(
                kbSection: new KnowledgeBaseSectionUpdateData(
                    sectionTitle: [
                        '1' => 'Тестовый раздел',
                        '2' => 'Test Section',
                    ],
                    sectionDescription: [
                        '1' => 'Тестовое описание раздела',
                        '2' => 'Test section description',
                    ],
                    categoryId: 3,
                )
            ),
        ];

        yield 'minimal data' => [
            'data' => [
                'kb_section' => [
                    'section_title' => 'Minimal Update',
                    'section_description' => 'Minimal description',
                    'category_id' => 1,
                ],
            ],

            'expected' => new UpdateKnowledgeBaseSectionPayload(
                kbSection: new KnowledgeBaseSectionUpdateData(
                    sectionTitle: 'Minimal Update',
                    sectionDescription: 'Minimal description',
                    categoryId: 1,
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testValidateAndCreate(array $data, UpdateKnowledgeBaseSectionPayload $expected): void
    {
        $actual = UpdateKnowledgeBaseSectionPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
