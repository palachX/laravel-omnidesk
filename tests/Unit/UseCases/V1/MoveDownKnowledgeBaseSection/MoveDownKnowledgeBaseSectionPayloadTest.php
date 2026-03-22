<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\MoveDownKnowledgeBaseSection;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\MoveDownKnowledgeBaseSection\Payload as MoveDownKnowledgeBaseSectionPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class MoveDownKnowledgeBaseSectionPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['section_id' => 1],
            'expected' => new MoveDownKnowledgeBaseSectionPayload(sectionId: 1),
        ];

        yield 'medium id' => [
            'data' => ['section_id' => 12345],
            'expected' => new MoveDownKnowledgeBaseSectionPayload(sectionId: 12345),
        ];

        yield 'large id' => [
            'data' => ['section_id' => 999999999],
            'expected' => new MoveDownKnowledgeBaseSectionPayload(sectionId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, MoveDownKnowledgeBaseSectionPayload $expected): void
    {
        $actual = MoveDownKnowledgeBaseSectionPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
