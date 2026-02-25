<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateMessage;

use Palach\Omnidesk\DTO\FileData;
use Palach\Omnidesk\DTO\MessageData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateMessage\Response as UpdateMessageResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateMessageResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'message' => [
                    'message_id' => 2000,
                    'user_id' => 123,
                    'staff_id' => 321,
                    'content' => 'I need help',
                    'content_html' => '<p>I need help!</p>',
                    'attachments' => [
                        [
                            'file_id' => 345,
                            'file_name' => 'contract.pdf',
                            'file_size' => 40863,
                            'mime_type' => 'application/pdf',
                            'url' => 'https://[domain].omnidesk.ru/some_path_here/345',
                        ],
                        [
                            'file_id' => 346,
                            'file_name' => 'invoice.pdf',
                            'file_size' => 50863,
                            'mime_type' => 'application/pdf',
                            'url' => 'https://[domain].omnidesk.ru/some_path_here/346',
                        ],
                    ],
                    'note' => false,
                    'created_at' => 'Mon, 06 May 2014 00:15:17 +0300',
                ],
            ],

            'expected' => new UpdateMessageResponse(
                message: new MessageData(
                    messageId: 2000,
                    userId: 123,
                    staffId: 321,
                    content: 'I need help',
                    contentHtml: '<p>I need help!</p>',
                    note: false,
                    createdAt: 'Mon, 06 May 2014 00:15:17 +0300',
                    attachments: [
                        new FileData(
                            fileId: 345,
                            fileName: 'contract.pdf',
                            fileSize: 40863,
                            mimeType: 'application/pdf',
                            url: 'https://[domain].omnidesk.ru/some_path_here/345'
                        ),
                        new FileData(
                            fileId: 346,
                            fileName: 'invoice.pdf',
                            fileSize: 50863,
                            mimeType: 'application/pdf',
                            url: 'https://[domain].omnidesk.ru/some_path_here/346'
                        ),
                    ]
                )
            ),
        ];
        yield 'required data' => [
            'data' => [
                'message' => [
                    'message_id' => 2000,
                    'user_id' => 123,
                    'staff_id' => 321,
                    'content' => 'I need help',
                    'content_html' => '<p>I need help!</p>',
                    'note' => false,
                    'created_at' => 'Mon, 06 May 2014 00:15:17 +0300',
                ],
            ],

            'expected' => new UpdateMessageResponse(
                message: new MessageData(
                    messageId: 2000,
                    userId: 123,
                    staffId: 321,
                    content: 'I need help',
                    contentHtml: '<p>I need help!</p>',
                    note: false,
                    createdAt: 'Mon, 06 May 2014 00:15:17 +0300',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, UpdateMessageResponse $expected): void
    {
        $actual = UpdateMessageResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
