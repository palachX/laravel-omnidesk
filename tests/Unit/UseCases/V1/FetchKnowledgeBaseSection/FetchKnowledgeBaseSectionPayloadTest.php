<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchKnowledgeBaseSection;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSection\Payload as FetchKnowledgeBaseSectionPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchKnowledgeBaseSectionPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'valid section id' => [
            'data' => [
                'section_id' => 10,
            ],
            'expected' => new FetchKnowledgeBaseSectionPayload(
                sectionId: 10,
            ),
        ];

        yield 'section id with language' => [
            'data' => [
                'section_id' => 20,
                'language_id' => '2',
            ],
            'expected' => new FetchKnowledgeBaseSectionPayload(
                sectionId: 20,
                languageId: '2',
            ),
        ];

        yield 'section id with all languages' => [
            'data' => [
                'section_id' => 30,
                'language_id' => 'all',
            ],
            'expected' => new FetchKnowledgeBaseSectionPayload(
                sectionId: 30,
                languageId: 'all',
            ),
        ];

        yield 'minimum section id' => [
            'data' => [
                'section_id' => 1,
            ],
            'expected' => new FetchKnowledgeBaseSectionPayload(
                sectionId: 1,
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchKnowledgeBaseSectionPayload $expected): void
    {
        $actual = FetchKnowledgeBaseSectionPayload::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }
}
