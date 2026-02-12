<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreMessage;

use Palach\Omnidesk\DTO\AttachmentData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreMessage\MessageStoreData;
use Palach\Omnidesk\UseCases\V1\StoreMessage\Payload as StoreMessagePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreMessagePayloadTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'case_id' => 123,
                'message' => [
                    'content' => 'I need help!',
                    'content_html' => '<p>I need help!</p>',
                    'user_id' => 321,
                    'staff_id' => 456,
                    'created_at' => 'Mon, 06 May 2014 00:15:17 +0300',
                    'send_at' => 'Mon, 06 May 2014 00:15:17 +0300',
                    'attachment_urls' => [
                        'https://abcompany.ru/548899/contract.pdf',
                        'https://abcompany.ru/548899/invoice.pdf',
                    ],
                ],
            ],

            'expected' => new StoreMessagePayload(
                caseId: 123,
                message: new MessageStoreData(
                    content: 'I need help!',
                    contentHtml: '<p>I need help!</p>',
                    staffId: 456,
                    userId: 321,
                    createdAt: 'Mon, 06 May 2014 00:15:17 +0300',
                    attachmentUrls: [
                        'https://abcompany.ru/548899/contract.pdf',
                        'https://abcompany.ru/548899/invoice.pdf',
                    ],
                    sendAt: 'Mon, 06 May 2014 00:15:17 +0300'
                )
            ),
        ];

        yield 'required data' => [
            'data' => [
                'case_id' => 123,
                'message' => [
                    'content' => 'I need help!',
                    'user_id' => 321,
                    'content_html' => '<p>I need help!</p>',
                ],
            ],

            'expected' => new StoreMessagePayload(
                caseId: 123,
                message: new MessageStoreData(
                    content: 'I need help!',
                    contentHtml: '<p>I need help!</p>',
                    userId: 321,
                )
            ),
        ];

        yield 'required data with attachments' => [
            'data' => [
                'case_id' => 123,
                'message' => [
                    'content' => 'I need help!',
                    'content_html' => '<p>I need help!</p>',
                    'user_id' => 321,
                    'attachments' => [
                        [
                            'name' => 'file.txt',
                            'mime_type' => 'text/plain',
                            'content' => 'file-content',
                        ],
                    ],
                ],
            ],

            'expected' => new StoreMessagePayload(
                caseId: 123,
                message: new MessageStoreData(
                    content: 'I need help!',
                    contentHtml: '<p>I need help!</p>',
                    userId: 321,
                    attachments: [
                        new AttachmentData(
                            name: 'file.txt',
                            mimeType: 'text/plain',
                            content: 'file-content'
                        ),
                    ],
                )
            ),
        ];
    }

    public static function dataMultipartProvider(): iterable
    {
        yield 'without attachments' => [
            'payload' => new StoreMessagePayload(
                caseId: 123,
                message: new MessageStoreData(
                    content: 'I need help!',
                    contentHtml: '<p>I need help!</p>',
                    userId: 321,
                )
            ),

            'expected' => [
                [
                    'name' => 'message[content]',
                    'contents' => 'I need help!',
                ],
                [
                    'name' => 'message[content_html]',
                    'contents' => '<p>I need help!</p>',
                ],
                [
                    'name' => 'message[user_id]',
                    'contents' => '321',
                ],
            ],
        ];

        yield 'with attachment' => [
            'payload' => new StoreMessagePayload(
                caseId: 123,
                message: new MessageStoreData(
                    content: 'I need help!',
                    contentHtml: '<p>I need help!</p>',
                    userId: 321,
                    attachments: [
                        new AttachmentData(
                            name: 'file.txt',
                            mimeType: 'text/plain',
                            content: 'file-content'
                        ),
                    ],
                )
            ),

            'expected' => [
                [
                    'name' => 'message[content]',
                    'contents' => 'I need help!',
                ],
                [
                    'name' => 'message[content_html]',
                    'contents' => '<p>I need help!</p>',
                ],
                [
                    'name' => 'message[user_id]',
                    'contents' => '321',
                ],
                [
                    'name' => 'message[attachments][0]',
                    'contents' => 'file-content',
                    'filename' => 'file.txt',
                    'headers' => [
                        'Content-Type' => 'text/plain',
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testFromArray(array $data, StoreMessagePayload $expected): void
    {
        $actual = StoreMessagePayload::validateAndCreate($data);
        $this->assertEquals($expected->toArray(), $actual->toArray());
    }

    #[DataProvider('dataMultipartProvider')]
    public function testToMultipart(StoreMessagePayload $payload, array $expected): void
    {
        $this->assertSame($expected, $payload->toMultipart());
    }
}
