<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\EnableKnowledgeBaseSection;

use Palach\Omnidesk\DTO\KnowledgeBaseSectionData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\EnableKnowledgeBaseSection\Response as EnableKnowledgeBaseSectionResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class EnableKnowledgeBaseSectionResponseTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'enable knowledge base section response' => [
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
            'expected' => [
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
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testResponse(array $data, array $expected): void
    {
        $response = EnableKnowledgeBaseSectionResponse::from($data);

        $this->assertInstanceOf(KnowledgeBaseSectionData::class, $response->kbSection);
        $this->assertEquals($expected, $response->toArray());
    }
}
