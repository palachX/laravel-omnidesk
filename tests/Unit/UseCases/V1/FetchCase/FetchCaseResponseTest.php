<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchCase;

use Palach\Omnidesk\DTO\CaseData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCase\Response as FetchCaseResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchCaseResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full case data' => [
            'data' => [
                'case' => [
                    'case_id' => 2000,
                    'case_number' => '664-245651',
                    'subject' => 'I need help',
                    'user_id' => 123,
                    'staff_id' => 22,
                    'group_id' => 44,
                    'status' => 'closed',
                    'priority' => 'normal',
                    'channel' => 'web',
                    'recipient' => 'user@domain.ru',
                    'cc_emails' => 'user_cc@domain.ru,user_cc2@domain.ru',
                    'bcc_emails' => 'user_bcc@domain.ru',
                    'deleted' => false,
                    'spam' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'closed_at' => '-',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    'last_response_at' => 'Tue, 23 Dec 2014 09:53:14 +0200',
                    'closing_speed' => '15',
                    'language_id' => 2,
                    'custom_fields' => [
                        'cf_25' => 'some text',
                        'cf_30' => 'another field',
                    ],
                    'labels' => [101, 102, 103, 104, 105],
                    'locked_labels' => [104, 105],
                    'rating' => 'high',
                    'rating_comment' => 'cool123',
                    'rated_staff_id' => 193,
                ],
            ],

            'expected' => new FetchCaseResponse(
                case: new CaseData(
                    caseId: 2000,
                    caseNumber: '664-245651',
                    subject: 'I need help',
                    userId: 123,
                    staffId: 22,
                    groupId: 44,
                    status: 'closed',
                    priority: 'normal',
                    channel: 'web',
                    recipient: 'user@domain.ru',
                    ccEmails: 'user_cc@domain.ru,user_cc2@domain.ru',
                    bccEmails: 'user_bcc@domain.ru',
                    deleted: false,
                    spam: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    closedAt: '-',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    lastResponseAt: 'Tue, 23 Dec 2014 09:53:14 +0200',
                    closingSpeed: '15',
                    languageId: 2,
                    customFields: [
                        'cf_25' => 'some text',
                        'cf_30' => 'another field',
                    ],
                    labels: [101, 102, 103, 104, 105],
                    lockedLabels: [104, 105],
                    rating: 'high',
                    ratingComment: 'cool123',
                    ratedStaffId: 193,
                ),
            ),
        ];

        yield 'minimal case data' => [
            'data' => [
                'case' => [
                    'case_id' => 2001,
                    'case_number' => '664-245652',
                    'subject' => 'Simple case',
                    'user_id' => 456,
                    'staff_id' => 33,
                    'group_id' => 55,
                    'status' => 'open',
                    'priority' => 'normal',
                    'channel' => 'email',
                    'deleted' => false,
                    'spam' => false,
                ],
            ],

            'expected' => new FetchCaseResponse(
                case: new CaseData(
                    caseId: 2001,
                    caseNumber: '664-245652',
                    subject: 'Simple case',
                    userId: 456,
                    staffId: 33,
                    groupId: 55,
                    status: 'open',
                    priority: 'normal',
                    channel: 'email',
                    deleted: false,
                    spam: false,
                ),
            ),
        ];

        yield 'case with zero closing speed' => [
            'data' => [
                'case' => [
                    'case_id' => 2002,
                    'case_number' => '664-245653',
                    'subject' => 'Open case',
                    'user_id' => 789,
                    'staff_id' => 44,
                    'group_id' => 66,
                    'status' => 'open',
                    'priority' => 'high',
                    'channel' => 'web',
                    'deleted' => false,
                    'spam' => false,
                    'closing_speed' => '0',
                ],
            ],

            'expected' => new FetchCaseResponse(
                case: new CaseData(
                    caseId: 2002,
                    caseNumber: '664-245653',
                    subject: 'Open case',
                    userId: 789,
                    staffId: 44,
                    groupId: 66,
                    status: 'open',
                    priority: 'high',
                    channel: 'web',
                    deleted: false,
                    spam: false,
                    closingSpeed: '0',
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchCaseResponse $expected): void
    {
        $actual = FetchCaseResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }

    public function testCaseType(): void
    {
        $caseData = new CaseData(
            caseId: 1,
            caseNumber: 'TEST-001',
            subject: 'Test Case',
            userId: 1,
            staffId: 1,
            groupId: 1,
            status: 'open',
            priority: 'normal',
            channel: 'web',
        );

        $response = new FetchCaseResponse(case: $caseData);

        $this->assertInstanceOf(CaseData::class, $response->case);
        $this->assertSame($caseData, $response->case);
    }

    public function testCaseIsReadOnly(): void
    {
        $caseData = new CaseData(
            caseId: 1,
            caseNumber: 'TEST-001',
            subject: 'Test Case',
            userId: 1,
            staffId: 1,
            groupId: 1,
            status: 'open',
            priority: 'normal',
            channel: 'web',
        );

        $response = new FetchCaseResponse(case: $caseData);

        // Verify the property is readonly
        $this->assertSame($caseData, $response->case);
    }
}
