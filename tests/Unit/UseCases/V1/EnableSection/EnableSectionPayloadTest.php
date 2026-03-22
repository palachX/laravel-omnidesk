<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\EnableSection;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\EnableKnowledgeBaseSection\Payload as EnableSectionPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class EnableSectionPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['section_id' => 1],
            'expected' => new EnableSectionPayload(sectionId: 1),
        ];

        yield 'medium id' => [
            'data' => ['section_id' => 12345],
            'expected' => new EnableSectionPayload(sectionId: 12345),
        ];

        yield 'large id' => [
            'data' => ['section_id' => 999999999],
            'expected' => new EnableSectionPayload(sectionId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, EnableSectionPayload $expected): void
    {
        $actual = EnableSectionPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
