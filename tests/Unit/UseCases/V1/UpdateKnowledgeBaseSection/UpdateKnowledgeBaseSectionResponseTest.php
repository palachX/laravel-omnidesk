<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateKnowledgeBaseSection;

use Palach\Omnidesk\DTO\KnowledgeBaseSectionData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseSection\Response as UpdateKnowledgeBaseSectionResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateKnowledgeBaseSectionResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'string title and description' => [
            'data' => [
                'kb_section' => [
                    'section_id' => 10,
                    'category_id' => 2,
                    'section_title' => 'Test section 2',
                    'section_description' => 'Test section description 2',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new UpdateKnowledgeBaseSectionResponse(
                kbSection: new KnowledgeBaseSectionData(
                    sectionId: 10,
                    categoryId: 2,
                    sectionTitle: 'Test section 2',
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    sectionDescription: 'Test section description 2',
                )
            ),
        ];

        yield 'multilingual title and description' => [
            'data' => [
                'kb_section' => [
                    'section_id' => 11,
                    'category_id' => 2,
                    'section_title' => [
                        '1' => 'Тест 2',
                        '2' => 'Test section 2',
                    ],
                    'section_description' => [
                        '1' => 'Тест описание 2',
                        '2' => 'Test section description 2',
                    ],
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new UpdateKnowledgeBaseSectionResponse(
                kbSection: new KnowledgeBaseSectionData(
                    sectionId: 11,
                    categoryId: 2,
                    sectionTitle: [
                        '1' => 'Тест 2',
                        '2' => 'Test section 2',
                    ],
                    sectionDescription: [
                        '1' => 'Тест описание 2',
                        '2' => 'Test section description 2',
                    ],
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                )
            ),
        ];

        yield 'inactive section' => [
            'data' => [
                'kb_section' => [
                    'section_id' => 12,
                    'category_id' => 3,
                    'section_title' => 'Updated Section Name',
                    'section_description' => 'Updated section description',
                    'active' => false,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Fri, 26 Dec 2014 11:20:00 +0200',
                ],
            ],

            'expected' => new UpdateKnowledgeBaseSectionResponse(
                kbSection: new KnowledgeBaseSectionData(
                    sectionId: 12,
                    categoryId: 3,
                    sectionTitle: 'Updated Section Name',
                    sectionDescription: 'Updated section description',
                    active: false,
                    createdAt: 'Thu, 20 Jul 2023 09:15:00 +0300',
                    updatedAt: 'Fri, 26 Dec 2014 11:20:00 +0200',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testValidateAndCreate(array $data, UpdateKnowledgeBaseSectionResponse $expected): void
    {
        $actual = UpdateKnowledgeBaseSectionResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
