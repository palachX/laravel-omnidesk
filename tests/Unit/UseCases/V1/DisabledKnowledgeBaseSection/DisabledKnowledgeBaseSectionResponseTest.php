<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DisabledKnowledgeBaseSection;

use Palach\Omnidesk\DTO\KnowledgeBaseSectionData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisabledKnowledgeBaseSection\Response as DisabledKnowledgeBaseSectionResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class DisabledKnowledgeBaseSectionResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'disable knowledge base section response' => [
            'data' => [
                'kb_section' => [
                    'section_id' => 10,
                    'category_id' => 2,
                    'section_title' => 'Test section 2',
                    'section_description' => 'Test section description 2',
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
            'expected' => new DisabledKnowledgeBaseSectionResponse(
                kbSection: new KnowledgeBaseSectionData(
                    sectionId: 10,
                    categoryId: 2,
                    sectionTitle: 'Test section 2',
                    active: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    sectionDescription: 'Test section description 2',
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DisabledKnowledgeBaseSectionResponse $expected): void
    {
        $actual = DisabledKnowledgeBaseSectionResponse::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }
}
