<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\SpamCase;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\SpamCase\BulkPayload as SpamCaseBulkPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class SpamCaseBulkPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single case id' => [
            'data' => ['case_ids' => [1]],
            'expected' => new SpamCaseBulkPayload(caseIds: [1]),
        ];

        yield 'multiple case ids' => [
            'data' => ['case_ids' => [123, 456, 789]],
            'expected' => new SpamCaseBulkPayload(caseIds: [123, 456, 789]),
        ];

        yield 'maximum allowed case ids' => [
            'data' => ['case_ids' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
            'expected' => new SpamCaseBulkPayload(caseIds: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, SpamCaseBulkPayload $expected): void
    {
        $actual = SpamCaseBulkPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }

    public function testTooManyCaseIds(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum 10 case IDs allowed per request');

        new SpamCaseBulkPayload(caseIds: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]);
    }
}
