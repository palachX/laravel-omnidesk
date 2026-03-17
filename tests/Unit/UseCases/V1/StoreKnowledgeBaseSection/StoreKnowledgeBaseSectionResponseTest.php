<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreKnowledgeBaseSection;

use Palach\Omnidesk\DTO\KnowledgeBaseSectionData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseSection\Response as StoreKnowledgeBaseSectionResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreKnowledgeBaseSectionResponseTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'kb section response title single language' => [
            'data' => [
                'kb_section' => [
                    'section_id' => 10,
                    'category_id' => 1,
                    'section_title' => 'Test section',
                    'section_description' => 'Test section description',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
            'expected' => new StoreKnowledgeBaseSectionResponse(
                kbSection: new KnowledgeBaseSectionData(
                    sectionId: 10,
                    categoryId: 1,
                    sectionTitle: 'Test section',
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    sectionDescription: 'Test section description',
                )
            ),
        ];

        yield 'kb section response title multiple languages' => [
            'data' => [
                'kb_section' => [
                    'section_id' => 10,
                    'category_id' => 1,
                    'section_title' => [
                        '1' => 'Тестовый раздел',
                        '2' => 'Test section',
                    ],
                    'section_description' => [
                        '1' => 'Тестовое описание раздела',
                        '2' => 'Test section description',
                    ],
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
            'expected' => new StoreKnowledgeBaseSectionResponse(
                kbSection: new KnowledgeBaseSectionData(
                    sectionId: 10,
                    categoryId: 1,
                    sectionTitle: [
                        '1' => 'Тестовый раздел',
                        '2' => 'Test section',
                    ],
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    sectionDescription: [
                        '1' => 'Тестовое описание раздела',
                        '2' => 'Test section description',
                    ],
                )
            ),
        ];

        yield 'kb section response without description' => [
            'data' => [
                'kb_section' => [
                    'section_id' => 10,
                    'category_id' => 1,
                    'section_title' => 'Test section',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
            'expected' => new StoreKnowledgeBaseSectionResponse(
                kbSection: new KnowledgeBaseSectionData(
                    sectionId: 10,
                    categoryId: 1,
                    sectionTitle: 'Test section',
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                )
            ),
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testFromArray(array $data, StoreKnowledgeBaseSectionResponse $expected): void
    {
        $response = StoreKnowledgeBaseSectionResponse::validateAndCreate($data);

        $this->assertEquals($expected, $response);
    }
}
