<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DeleteKnowledgeBaseSection;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteKnowledgeBaseSection\Payload as DeleteKnowledgeBaseSectionPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DeleteKnowledgeBaseSectionPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['section_id' => 1],
            'expected' => new DeleteKnowledgeBaseSectionPayload(sectionId: 1),
        ];

        yield 'medium id' => [
            'data' => ['section_id' => 12345],
            'expected' => new DeleteKnowledgeBaseSectionPayload(sectionId: 12345),
        ];

        yield 'large id' => [
            'data' => ['section_id' => 999999999],
            'expected' => new DeleteKnowledgeBaseSectionPayload(sectionId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DeleteKnowledgeBaseSectionPayload $expected): void
    {
        $actual = DeleteKnowledgeBaseSectionPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
