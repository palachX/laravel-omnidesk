<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchKnowledgeBaseSection;

use Palach\Omnidesk\DTO\KnowledgeBaseSectionData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSection\Response as FetchKnowledgeBaseSectionResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchKnowledgeBaseSectionResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full section data with single language' => [
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

            'expected' => new FetchKnowledgeBaseSectionResponse(
                kbSection: new KnowledgeBaseSectionData(
                    sectionId: 10,
                    categoryId: 1,
                    sectionTitle: 'Test section',
                    sectionDescription: 'Test section description',
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                ),
            ),
        ];

        yield 'full section data with multiple languages' => [
            'data' => [
                'kb_section' => [
                    'section_id' => 10,
                    'category_id' => 1,
                    'section_title' => [
                        '1' => 'Тест 1',
                        '2' => 'Test section 1',
                    ],
                    'section_description' => [
                        '1' => 'Тест описание 1',
                        '2' => 'Test section description 1',
                    ],
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new FetchKnowledgeBaseSectionResponse(
                kbSection: new KnowledgeBaseSectionData(
                    sectionId: 10,
                    categoryId: 1,
                    sectionTitle: [
                        '1' => 'Тест 1',
                        '2' => 'Test section 1',
                    ],
                    sectionDescription: [
                        '1' => 'Тест описание 1',
                        '2' => 'Test section description 1',
                    ],
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                ),
            ),
        ];

        yield 'minimal section data' => [
            'data' => [
                'kb_section' => [
                    'section_id' => 20,
                    'category_id' => 2,
                    'section_title' => 'Simple section',
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new FetchKnowledgeBaseSectionResponse(
                kbSection: new KnowledgeBaseSectionData(
                    sectionId: 20,
                    categoryId: 2,
                    sectionTitle: 'Simple section',
                    active: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchKnowledgeBaseSectionResponse $expected): void
    {
        $actual = FetchKnowledgeBaseSectionResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
