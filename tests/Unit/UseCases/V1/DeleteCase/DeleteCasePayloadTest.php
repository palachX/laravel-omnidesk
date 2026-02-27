<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DeleteCase;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteCase\Payload as DeleteCasePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DeleteCasePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['case_id' => 1],
            'expected' => new DeleteCasePayload(caseId: 1),
        ];

        yield 'medium id' => [
            'data' => ['case_id' => 12345],
            'expected' => new DeleteCasePayload(caseId: 12345),
        ];

        yield 'large id' => [
            'data' => ['case_id' => 999999999],
            'expected' => new DeleteCasePayload(caseId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DeleteCasePayload $expected): void
    {
        $actual = DeleteCasePayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
