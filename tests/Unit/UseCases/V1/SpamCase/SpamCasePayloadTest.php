<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\SpamCase;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\SpamCase\Payload as SpamCasePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class SpamCasePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['case_id' => 1],
            'expected' => new SpamCasePayload(caseId: 1),
        ];

        yield 'medium id' => [
            'data' => ['case_id' => 12345],
            'expected' => new SpamCasePayload(caseId: 12345),
        ];

        yield 'large id' => [
            'data' => ['case_id' => 999999999],
            'expected' => new SpamCasePayload(caseId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, SpamCasePayload $expected): void
    {
        $actual = SpamCasePayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
