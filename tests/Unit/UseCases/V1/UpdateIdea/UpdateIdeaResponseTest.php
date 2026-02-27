<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateIdea;

use Palach\Omnidesk\DTO\CaseData;
use Palach\Omnidesk\DTO\IdeaData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateIdea\Response as UpdateIdeaResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateIdeaResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data with idea' => [
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
                    'channel' => 'idea',
                    'recipient' => '',
                    'cc_emails' => '',
                    'bcc_emails' => '',
                    'deleted' => false,
                    'spam' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    'idea' => [
                        'content' => 'New content',
                        'stage' => 'planned',
                        'category' => 319,
                    ],
                ],
            ],

            'expected' => new UpdateIdeaResponse(
                case: new CaseData(
                    caseId: 2000,
                    caseNumber: '664-245651',
                    subject: 'I need help',
                    userId: 123,
                    staffId: 22,
                    groupId: 44,
                    status: 'waiting',
                    priority: 'normal',
                    channel: 'idea',
                    recipient: '',
                    ccEmails: '',
                    bccEmails: '',
                    deleted: false,
                    spam: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    idea: new IdeaData(
                        content: 'New content',
                        stage: 'planned',
                        category: 319
                    )
                )
            ),
        ];

        yield 'required data without idea' => [
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
                    'channel' => 'idea',
                    'deleted' => false,
                    'spam' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new UpdateIdeaResponse(
                case: new CaseData(
                    caseId: 2000,
                    caseNumber: '664-245651',
                    subject: 'I need help',
                    userId: 123,
                    staffId: 22,
                    groupId: 44,
                    status: 'waiting',
                    priority: 'normal',
                    channel: 'idea',
                    deleted: false,
                    spam: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                )
            ),
        ];

        yield 'partial idea data' => [
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
                    'channel' => 'idea',
                    'deleted' => false,
                    'spam' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    'idea' => [
                        'stage' => 'finished',
                    ],
                ],
            ],

            'expected' => new UpdateIdeaResponse(
                case: new CaseData(
                    caseId: 2000,
                    caseNumber: '664-245651',
                    subject: 'I need help',
                    userId: 123,
                    staffId: 22,
                    groupId: 44,
                    status: 'waiting',
                    priority: 'normal',
                    channel: 'idea',
                    deleted: false,
                    spam: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                    idea: new IdeaData(
                        stage: 'finished'
                    )
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, UpdateIdeaResponse $expected): void
    {
        $actual = UpdateIdeaResponse::from($data);

        $this->assertEquals($expected, $actual);
    }
}
