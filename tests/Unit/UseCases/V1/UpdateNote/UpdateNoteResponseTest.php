<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateNote;

use Palach\Omnidesk\DTO\FileData;
use Palach\Omnidesk\DTO\MessageData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateNote\Response as UpdateNoteResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateNoteResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'message' => [
                    'message_id' => 2000,
                    'user_id' => 123,
                    'staff_id' => 321,
                    'content' => 'Updated note content',
                    'content_html' => '<p>Updated note content</p>',
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
                    'note' => true,
                    'created_at' => 'Mon, 06 May 2014 00:15:17 +0300',
                ],
            ],

            'expected' => new UpdateNoteResponse(
                message: new MessageData(
                    messageId: 2000,
                    userId: 123,
                    staffId: 321,
                    content: 'Updated note content',
                    contentHtml: '<p>Updated note content</p>',
                    note: true,
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
                    'content' => 'Updated note content',
                    'content_html' => '<p>Updated note content</p>',
                    'note' => true,
                    'created_at' => 'Mon, 06 May 2014 00:15:17 +0300',
                ],
            ],

            'expected' => new UpdateNoteResponse(
                message: new MessageData(
                    messageId: 2000,
                    userId: 123,
                    staffId: 321,
                    content: 'Updated note content',
                    contentHtml: '<p>Updated note content</p>',
                    note: true,
                    createdAt: 'Mon, 06 May 2014 00:15:17 +0300',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, UpdateNoteResponse $expected): void
    {
        $actual = UpdateNoteResponse::from($data);

        $this->assertEquals($expected, $actual);
    }
}
