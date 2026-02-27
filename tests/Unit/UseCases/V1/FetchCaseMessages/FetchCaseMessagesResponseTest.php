<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchCaseMessages;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\FileData;
use Palach\Omnidesk\DTO\MessageData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCaseMessages\Response as FetchCaseMessagesResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchCaseMessagesResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data with attachments' => [
            'data' => [
                'messages' => [
                    [
                        'message_id' => 2000,
                        'user_id' => 123,
                        'staff_id' => 0,
                        'content' => '',
                        'content_html' => 'Тестовый ответ пользователя',
                        'attachments' => [
                            [
                                'file_id' => 345,
                                'file_name' => 'test.jpg',
                                'file_size' => 40863,
                                'mime_type' => 'image/jpeg',
                                'url' => 'https://example.omnidesk.ru/some_path_here/345',
                            ],
                        ],
                        'note' => false,
                        'sent_via_rule' => false,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'sent_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'rating' => 'high',
                        'rating_comment' => 'cool123',
                        'rated_staff_id' => 0,
                    ],
                    [
                        'message_id' => 200198234,
                        'user_id' => 0,
                        'staff_id' => 41094,
                        'content' => '',
                        'content_html' => 'Тестовый ответ сотрудника',
                        'attachments' => [],
                        'note' => false,
                        'sent_via_rule' => false,
                        'created_at' => 'Mon, 15 May 2023 09:28:43 +0300',
                        'sent_at' => 'Mon, 15 May 2023 10:15:17 +0300',
                    ],
                ],
                'total_count' => 17,
            ],

            'expected' => new FetchCaseMessagesResponse(
                messages: new Collection([
                    new MessageData(
                        messageId: 2000,
                        userId: 123,
                        staffId: 0,
                        note: false,
                        createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                        content: '',
                        contentHtml: 'Тестовый ответ пользователя',
                        attachments: [
                            new FileData(
                                fileId: 345,
                                fileName: 'test.jpg',
                                fileSize: 40863,
                                mimeType: 'image/jpeg',
                                url: 'https://example.omnidesk.ru/some_path_here/345',
                            ),
                        ],
                        rating: 'high',
                        ratingComment: 'cool123',
                        ratedStaffId: 0,
                        sentViaRule: false,
                        sentAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    ),
                    new MessageData(
                        messageId: 200198234,
                        userId: 0,
                        staffId: 41094,
                        note: false,
                        createdAt: 'Mon, 15 May 2023 09:28:43 +0300',
                        content: '',
                        contentHtml: 'Тестовый ответ сотрудника',
                        attachments: [],
                        sentViaRule: false,
                        sentAt: 'Mon, 15 May 2023 10:15:17 +0300',
                    ),
                ]),
                totalCount: 17
            ),
        ];

        yield 'minimal data' => [
            'data' => [
                'messages' => [
                    [
                        'message_id' => 2000,
                        'user_id' => 123,
                        'staff_id' => 0,
                        'content' => 'Simple message',
                        'note' => false,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    ],
                ],
                'total_count' => 1,
            ],

            'expected' => new FetchCaseMessagesResponse(
                messages: new Collection([
                    new MessageData(
                        messageId: 2000,
                        userId: 123,
                        staffId: 0,
                        note: false,
                        createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                        content: 'Simple message',
                    ),
                ]),
                totalCount: 1
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchCaseMessagesResponse $expected): void
    {
        $actual = FetchCaseMessagesResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
