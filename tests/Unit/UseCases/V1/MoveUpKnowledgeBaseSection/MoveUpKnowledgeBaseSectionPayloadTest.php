<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\MoveUpKnowledgeBaseSection;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\MoveUpKnowledgeBaseSection\Payload as MoveUpKnowledgeBaseSectionPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class MoveUpKnowledgeBaseSectionPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['section_id' => 1],
            'expected' => new MoveUpKnowledgeBaseSectionPayload(sectionId: 1),
        ];

        yield 'medium id' => [
            'data' => ['section_id' => 12345],
            'expected' => new MoveUpKnowledgeBaseSectionPayload(sectionId: 12345),
        ];

        yield 'large id' => [
            'data' => ['section_id' => 999999999],
            'expected' => new MoveUpKnowledgeBaseSectionPayload(sectionId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, MoveUpKnowledgeBaseSectionPayload $expected): void
    {
        $actual = MoveUpKnowledgeBaseSectionPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
