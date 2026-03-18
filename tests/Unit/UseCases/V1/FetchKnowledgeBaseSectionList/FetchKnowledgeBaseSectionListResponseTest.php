<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchKnowledgeBaseSectionList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\KnowledgeBaseSectionData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSectionList\Response as FetchKnowledgeBaseSectionListResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchKnowledgeBaseSectionListResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data with multiple sections' => [
            'data' => [
                'kb_sections' => [
                    [
                        'section_id' => 10,
                        'category_id' => 1,
                        'section_title' => 'Test section',
                        'section_description' => 'Test section description',
                        'active' => true,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ],
                    [
                        'section_id' => 11,
                        'category_id' => 1,
                        'section_title' => 'Test section 2',
                        'section_description' => 'Test section description 2',
                        'active' => false,
                        'created_at' => 'Mon, 15 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 13 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total' => 10,
            ],

            'expected' => new FetchKnowledgeBaseSectionListResponse(
                kbSections: new Collection([
                    new KnowledgeBaseSectionData(
                        sectionId: 10,
                        categoryId: 1,
                        sectionTitle: 'Test section',
                        active: true,
                        createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                        updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                        sectionDescription: 'Test section description',
                    ),
                    new KnowledgeBaseSectionData(
                        sectionId: 11,
                        categoryId: 1,
                        sectionTitle: 'Test section 2',
                        active: false,
                        createdAt: 'Mon, 15 May 2014 00:15:17 +0300',
                        updatedAt: 'Tue, 13 Dec 2014 10:55:23 +0200',
                        sectionDescription: 'Test section description 2',
                    ),
                ]),
                total: 10
            ),
        ];

        yield 'multilingual sections' => [
            'data' => [
                'kb_sections' => [
                    [
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
                'total' => 5,
            ],

            'expected' => new FetchKnowledgeBaseSectionListResponse(
                kbSections: new Collection([
                    new KnowledgeBaseSectionData(
                        sectionId: 10,
                        categoryId: 1,
                        sectionTitle: [
                            '1' => 'Тест 1',
                            '2' => 'Test section 1',
                        ],
                        active: true,
                        createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                        updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                        sectionDescription: [
                            '1' => 'Тест описание 1',
                            '2' => 'Test section description 1',
                        ],
                    ),
                ]),
                total: 5
            ),
        ];

        yield 'empty sections list' => [
            'data' => [
                'kb_sections' => [],
                'total' => 0,
            ],

            'expected' => new FetchKnowledgeBaseSectionListResponse(
                kbSections: new Collection([]),
                total: 0
            ),
        ];

        yield 'single section' => [
            'data' => [
                'kb_sections' => [
                    [
                        'section_id' => 1,
                        'category_id' => 1,
                        'section_title' => 'Single section',
                        'section_description' => 'Single section description',
                        'active' => true,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total' => 1,
            ],

            'expected' => new FetchKnowledgeBaseSectionListResponse(
                kbSections: new Collection([
                    new KnowledgeBaseSectionData(
                        sectionId: 1,
                        categoryId: 1,
                        sectionTitle: 'Single section',
                        active: true,
                        createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                        updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                        sectionDescription: 'Single section description',
                    ),
                ]),
                total: 1
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchKnowledgeBaseSectionListResponse $expected): void
    {
        $actual = FetchKnowledgeBaseSectionListResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
