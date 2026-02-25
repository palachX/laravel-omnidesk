<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\TrashCase;

use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\TrashCase\BulkPayload as TrashCaseBulkPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class TrashCaseBulkPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single id' => [
            'data' => ['case_ids' => [2000]],
            'expected' => new TrashCaseBulkPayload(caseIds: [2000]),
        ];

        yield 'multiple ids' => [
            'data' => ['case_ids' => [101, 102, 103, 104]],
            'expected' => new TrashCaseBulkPayload(caseIds: [101, 102, 103, 104]),
        ];

        yield 'max allowed ids' => [
            'data' => ['case_ids' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
            'expected' => new TrashCaseBulkPayload(caseIds: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, TrashCaseBulkPayload $expected): void
    {
        $actual = TrashCaseBulkPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }

    public function testValidationMaxIds(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum 10 case IDs allowed per request');

        TrashCaseBulkPayload::validateAndCreate(['case_ids' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]]);
    }

    public function testValidationEmptyArray(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The case ids field is required.');

        TrashCaseBulkPayload::validateAndCreate(['case_ids' => []]);
    }
}
