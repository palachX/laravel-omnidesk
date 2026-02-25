<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\RestoreCase;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\RestoreCase\Payload as RestoreCasePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class RestoreCasePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['case_id' => 1],
            'expected' => new RestoreCasePayload(caseId: 1),
        ];

        yield 'medium id' => [
            'data' => ['case_id' => 12345],
            'expected' => new RestoreCasePayload(caseId: 12345),
        ];

        yield 'large id' => [
            'data' => ['case_id' => 999999999],
            'expected' => new RestoreCasePayload(caseId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, RestoreCasePayload $expected): void
    {
        $actual = RestoreCasePayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
