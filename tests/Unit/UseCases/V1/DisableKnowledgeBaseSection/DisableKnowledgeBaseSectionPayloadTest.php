<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DisableKnowledgeBaseSection;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisableSection\Payload as DisableSectionPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DisableKnowledgeBaseSectionPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['section_id' => 1],
            'expected' => new DisableSectionPayload(sectionId: 1),
        ];

        yield 'medium id' => [
            'data' => ['section_id' => 12345],
            'expected' => new DisableSectionPayload(sectionId: 12345),
        ];

        yield 'large id' => [
            'data' => ['section_id' => 999999999],
            'expected' => new DisableSectionPayload(sectionId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DisableSectionPayload $expected): void
    {
        $actual = DisableSectionPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
