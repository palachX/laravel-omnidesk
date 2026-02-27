<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\SpamCase;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\SpamCase\BulkResponse as SpamCaseBulkResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class SpamCaseBulkResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single success id' => [
            'data' => ['case_success_id' => [101]],
            'expected' => new SpamCaseBulkResponse(caseSuccessId: [101]),
        ];

        yield 'multiple success ids' => [
            'data' => ['case_success_id' => [101, 102, 103, 104]],
            'expected' => new SpamCaseBulkResponse(caseSuccessId: [101, 102, 103, 104]),
        ];

        yield 'empty success ids' => [
            'data' => ['case_success_id' => []],
            'expected' => new SpamCaseBulkResponse(caseSuccessId: []),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, SpamCaseBulkResponse $expected): void
    {
        $actual = SpamCaseBulkResponse::from($data);

        $this->assertEquals($expected, $actual);
    }
}
