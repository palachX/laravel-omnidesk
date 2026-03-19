<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DeleteKnowledgeBaseSection;

use Palach\Omnidesk\DTO\KnowledgeBaseSectionData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteKnowledgeBaseSection\Response as DeleteKnowledgeBaseSectionResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class DeleteKnowledgeBaseSectionResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'delete knowledge base section response' => [
            'data' => [
                'kb_section' => [
                    'section_id' => 300,
                    'category_id' => 100,
                    'section_title' => 'Test Section',
                    'section_description' => 'Test Description',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
            'expected' => new DeleteKnowledgeBaseSectionResponse(
                kbSection: new KnowledgeBaseSectionData(
                    sectionId: 300,
                    categoryId: 100,
                    sectionTitle: 'Test Section',
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    sectionDescription: 'Test Description',
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DeleteKnowledgeBaseSectionResponse $expected): void
    {
        $actual = DeleteKnowledgeBaseSectionResponse::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }
}
