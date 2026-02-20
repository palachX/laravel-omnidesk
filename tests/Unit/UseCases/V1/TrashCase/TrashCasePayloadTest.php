<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\TrashCase;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\TrashCase\Payload as TrashCasePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class TrashCasePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['case_id' => 1],
            'expected' => new TrashCasePayload(caseId: 1),
        ];

        yield 'medium id' => [
            'data' => ['case_id' => 12345],
            'expected' => new TrashCasePayload(caseId: 12345),
        ];

        yield 'large id' => [
            'data' => ['case_id' => 999999999],
            'expected' => new TrashCasePayload(caseId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, TrashCasePayload $expected): void
    {
        $actual = TrashCasePayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
