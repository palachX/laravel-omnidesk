<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DeleteCase;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteCase\BulkPayload as DeleteCaseBulkPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DeleteCaseBulkPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single case id' => [
            'data' => ['case_ids' => [1]],
            'expected' => new DeleteCaseBulkPayload(caseIds: [1]),
        ];

        yield 'multiple case ids' => [
            'data' => ['case_ids' => [123, 456, 789]],
            'expected' => new DeleteCaseBulkPayload(caseIds: [123, 456, 789]),
        ];

        yield 'maximum allowed case ids' => [
            'data' => ['case_ids' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
            'expected' => new DeleteCaseBulkPayload(caseIds: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DeleteCaseBulkPayload $expected): void
    {
        $actual = DeleteCaseBulkPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }

    public function testTooManyCaseIds(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum 10 case IDs allowed per request');

        new DeleteCaseBulkPayload(caseIds: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]);
    }
}
