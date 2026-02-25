<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\TrashCase;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\TrashCase\BulkResponse as TrashCaseBulkResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class TrashCaseBulkResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full success' => [
            'data' => [
                'case_success_id' => [101, 102, 103, 104],
            ],
            'expected' => new TrashCaseBulkResponse(
                caseSuccessId: [101, 102, 103, 104]
            ),
        ];

        yield 'partial success' => [
            'data' => [
                'case_success_id' => [101, 103, 104],
            ],
            'expected' => new TrashCaseBulkResponse(
                caseSuccessId: [101, 103, 104]
            ),
        ];

        yield 'single success' => [
            'data' => [
                'case_success_id' => [2000],
            ],
            'expected' => new TrashCaseBulkResponse(
                caseSuccessId: [2000]
            ),
        ];

        yield 'max success' => [
            'data' => [
                'case_success_id' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            ],
            'expected' => new TrashCaseBulkResponse(
                caseSuccessId: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, TrashCaseBulkResponse $expected): void
    {
        $actual = TrashCaseBulkResponse::from($data);
        $this->assertEquals($expected, $actual);
    }
}
