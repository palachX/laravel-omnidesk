<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\SpamCase;

use Palach\Omnidesk\DTO\CaseData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\SpamCase\Response as SpamCaseResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class SpamCaseResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'complete case data with spam true' => [
            'data' => [
                'case' => [
                    'case_id' => 2000,
                    'case_number' => '664-245651',
                    'subject' => 'Test subject changed',
                    'user_id' => 123,
                    'staff_id' => 22,
                    'group_id' => 44,
                    'status' => 'closed',
                    'priority' => 'critical',
                    'channel' => 'web',
                    'recipient' => 'user@domain.ru',
                    'deleted' => false,
                    'spam' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    'language_id' => 2,
                    'custom_fields' => [
                        'cf_25' => 'some text',
                        'cf_30' => 'another field',
                    ],
                    'labels' => [101, 102],
                ],
            ],
            'expected' => new SpamCaseResponse(
                case: new CaseData(
                    caseId: 2000,
                    caseNumber: '664-245651',
                    subject: 'Test subject changed',
                    userId: 123,
                    staffId: 22,
                    groupId: 44,
                    status: 'closed',
                    priority: 'critical',
                    channel: 'web',
                    recipient: 'user@domain.ru',
                    deleted: false,
                    spam: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    languageId: 2,
                    customFields: [
                        'cf_25' => 'some text',
                        'cf_30' => 'another field',
                    ],
                    labels: [101, 102]
                )
            ),
        ];

        yield 'minimal response with spam true' => [
            'data' => [
                'case' => [
                    'case_id' => 1001,
                    'case_number' => '123-456789',
                    'subject' => 'Simple case',
                    'user_id' => 456,
                    'staff_id' => 78,
                    'group_id' => 90,
                    'status' => 'open',
                    'priority' => 'normal',
                    'channel' => 'email',
                    'deleted' => false,
                    'spam' => true,
                ],
            ],
            'expected' => new SpamCaseResponse(
                case: new CaseData(
                    caseId: 1001,
                    caseNumber: '123-456789',
                    subject: 'Simple case',
                    userId: 456,
                    staffId: 78,
                    groupId: 90,
                    status: 'open',
                    priority: 'normal',
                    channel: 'email',
                    deleted: false,
                    spam: true
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, SpamCaseResponse $expected): void
    {
        $actual = SpamCaseResponse::from($data);

        $this->assertEquals($expected, $actual);
    }
}
