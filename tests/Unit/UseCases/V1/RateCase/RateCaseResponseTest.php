<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\RateCase;

use Palach\Omnidesk\DTO\CaseData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\RateCase\Response as RateCaseResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class RateCaseResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'case' => [
                    'case_id' => 2000,
                    'case_number' => '664-245651',
                    'subject' => 'I need help',
                    'user_id' => 123,
                    'staff_id' => 22,
                    'group_id' => 44,
                    'status' => 'waiting',
                    'priority' => 'normal',
                    'channel' => 'web',
                    'recipient' => 'user@domain.ru',
                    'cc_emails' => 'user_cc@domain.ru,user_cc2@domain.ru',
                    'bcc_emails' => 'user_bcc@domain.ru',
                    'deleted' => false,
                    'spam' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    'language_id' => 2,
                    'custom_fields' => [
                        'cf_25' => 'some text',
                        'cf_30' => 'another field',
                    ],
                    'labels' => [101, 102],
                    'rating' => 'high',
                    'rating_comment' => 'cool123',
                    'rated_staff_id' => 189,
                ],
            ],

            'expected' => new RateCaseResponse(
                case: new CaseData(
                    caseId: 2000,
                    caseNumber: '664-245651',
                    subject: 'I need help',
                    userId: 123,
                    staffId: 22,
                    groupId: 44,
                    status: 'waiting',
                    priority: 'normal',
                    channel: 'web',
                    recipient: 'user@domain.ru',
                    ccEmails: 'user_cc@domain.ru,user_cc2@domain.ru',
                    bccEmails: 'user_bcc@domain.ru',
                    deleted: false,
                    spam: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    languageId: 2,
                    customFields: [
                        'cf_25' => 'some text',
                        'cf_30' => 'another field',
                    ],
                    labels: [101, 102],
                    rating: 'high',
                    ratingComment: 'cool123',
                    ratedStaffId: 189,
                )
            ),
        ];

        yield 'minimal data' => [
            'data' => [
                'case' => [
                    'case_id' => 3000,
                    'case_number' => '123-456789',
                    'subject' => 'Help needed',
                    'user_id' => 456,
                    'staff_id' => 789,
                    'group_id' => 111,
                    'status' => 'closed',
                    'priority' => 'high',
                    'channel' => 'email',
                    'recipient' => 'client@example.com',
                    'deleted' => false,
                    'spam' => false,
                    'created_at' => 'Wed, 10 Jun 2015 12:30:00 +0300',
                    'updated_at' => 'Thu, 11 Jun 2015 15:45:00 +0300',
                    'language_id' => 1,
                    'rating' => 'middle',
                    'rating_comment' => 'Average service',
                    'rated_staff_id' => 789,
                ],
            ],

            'expected' => new RateCaseResponse(
                case: new CaseData(
                    caseId: 3000,
                    caseNumber: '123-456789',
                    subject: 'Help needed',
                    userId: 456,
                    staffId: 789,
                    groupId: 111,
                    status: 'closed',
                    priority: 'high',
                    channel: 'email',
                    recipient: 'client@example.com',
                    deleted: false,
                    spam: false,
                    createdAt: 'Wed, 10 Jun 2015 12:30:00 +0300',
                    updatedAt: 'Thu, 11 Jun 2015 15:45:00 +0300',
                    languageId: 1,
                    rating: 'middle',
                    ratingComment: 'Average service',
                    ratedStaffId: 789,
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, RateCaseResponse $expected): void
    {
        $actual = RateCaseResponse::from($data);

        $this->assertEquals($expected, $actual);
    }
}
