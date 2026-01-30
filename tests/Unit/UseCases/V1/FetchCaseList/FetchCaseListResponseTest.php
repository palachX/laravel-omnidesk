<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchCaseList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\CaseData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCaseList\Response as FetchCaseListResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchCaseListResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'cases' => [
                    [
                        'case_id' => 2000,
                        'case_number' => '664-245651',
                        'subject' => 'Договор и счёт',
                        'user_id' => 123,
                        'staff_id' => 321,
                        'group_id' => 444,
                        'status' => 'waiting',
                        'priority' => 'normal',
                        'channel' => 'chh21',
                        'deleted' => false,
                        'spam' => false,
                    ],
                ],
                'total' => 1,
            ],

            'expected' => new FetchCaseListResponse(
                cases: new Collection([
                    new CaseData(
                        caseId: 2000,
                        caseNumber: '664-245651',
                        subject: 'Договор и счёт',
                        userId: 123,
                        staffId: 321,
                        groupId: 444,
                        status: 'waiting',
                        priority: 'normal',
                        channel: 'chh21',
                        deleted: false,
                        spam: false
                    ),
                ]),
                total: 1
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchCaseListResponse $expected): void
    {
        $actual = FetchCaseListResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
