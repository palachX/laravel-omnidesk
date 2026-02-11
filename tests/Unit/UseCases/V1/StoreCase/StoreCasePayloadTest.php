<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreCase;

use Palach\Omnidesk\DTO\AttachmentData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreCase\CaseStoreData;
use Palach\Omnidesk\UseCases\V1\StoreCase\Payload as StoreCasePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreCasePayloadTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'full data email' => [
            'data' => [
                'case' => [
                    'user_email' => 'example@example.com',
                    'user_custom_id' => '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    'subject' => 'Subject case',
                    'content' => 'I need help',
                    'content_html' => '<p>I need help</p>',
                    'channel' => 'chh200',
                    'attachment_urls' => [
                        'https://abcompany.ru/548899/contract.pdf',
                        'https://abcompany.ru/548899/invoice.pdf',
                    ],
                ],
            ],

            'expected' => new StoreCasePayload(
                case: new CaseStoreData(
                    userCustomId: '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    subject: 'Subject case',
                    content: 'I need help',
                    contentHtml: '<p>I need help</p>',
                    channel: 'chh200',
                    userEmail: 'example@example.com',
                    attachmentUrls: [
                        'https://abcompany.ru/548899/contract.pdf',
                        'https://abcompany.ru/548899/invoice.pdf',
                    ]
                )
            ),
        ];

        yield 'full data phone' => [
            'data' => [
                'case' => [
                    'user_phone' => '+79998887755',
                    'user_custom_id' => '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    'subject' => 'Subject case',
                    'content' => 'I need help',
                    'content_html' => '<p>I need help</p>',
                    'channel' => 'chh200',
                    'attachment_urls' => [
                        'https://abcompany.ru/548899/contract.pdf',
                        'https://abcompany.ru/548899/invoice.pdf',
                    ],
                ],
            ],

            'expected' => new StoreCasePayload(
                case: new CaseStoreData(
                    userCustomId: '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    subject: 'Subject case',
                    content: 'I need help',
                    contentHtml: '<p>I need help</p>',
                    channel: 'chh200',
                    userPhone: '+79998887755',
                    attachmentUrls: [
                        'https://abcompany.ru/548899/contract.pdf',
                        'https://abcompany.ru/548899/invoice.pdf',
                    ]
                )
            ),
        ];

        yield 'full data email phone' => [
            'data' => [
                'case' => [
                    'user_email' => 'example@example.com',
                    'user_phone' => '+79998887755',
                    'user_custom_id' => '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    'subject' => 'Subject case',
                    'content' => 'I need help',
                    'content_html' => '<p>I need help</p>',
                    'channel' => 'chh200',
                    'attachment_urls' => [
                        'https://abcompany.ru/548899/contract.pdf',
                        'https://abcompany.ru/548899/invoice.pdf',
                    ],
                ],
            ],

            'expected' => new StoreCasePayload(
                case: new CaseStoreData(
                    userCustomId: '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    subject: 'Subject case',
                    content: 'I need help',
                    contentHtml: '<p>I need help</p>',
                    channel: 'chh200',
                    userEmail: 'example@example.com',
                    userPhone: '+79998887755',
                    attachmentUrls: [
                        'https://abcompany.ru/548899/contract.pdf',
                        'https://abcompany.ru/548899/invoice.pdf',
                    ]
                )
            ),
        ];

        yield 'full data email phone with attachments' => [
            'data' => [
                'case' => [
                    'user_custom_id' => '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    'subject' => 'Subject case',
                    'content' => 'I need help',
                    'content_html' => '<p>I need help</p>',
                    'channel' => 'chh200',
                    'user_email' => 'example@example.com',
                    'user_phone' => '+79998887755',
                    'attachments' => [
                        [
                            'name' => 'file.txt',
                            'mime_type' => 'text/plain',
                            'content' => 'file-content',
                        ],
                    ],
                ],
            ],

            'expected' => new StoreCasePayload(
                case: new CaseStoreData(
                    userCustomId: '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    subject: 'Subject case',
                    content: 'I need help',
                    contentHtml: '<p>I need help</p>',
                    channel: 'chh200',
                    userEmail: 'example@example.com',
                    userPhone: '+79998887755',
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

    public static function dataMultipartProviderSuccess(): iterable
    {
        yield 'multipart email' => [
            'payload' => new StoreCasePayload(
                case: new CaseStoreData(
                    userCustomId: '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    subject: 'Subject case',
                    content: 'I need help',
                    contentHtml: '<p>I need help</p>',
                    channel: 'chh200',
                    userEmail: 'example@example.com',
                )
            ),

            'expected' => [
                ['name' => 'case[user_custom_id]', 'contents' => '8e334869-a6ca-41da-b5cd-a8a51f99a529'],
                ['name' => 'case[subject]', 'contents' => 'Subject case'],
                ['name' => 'case[content]', 'contents' => 'I need help'],
                ['name' => 'case[content_html]', 'contents' => '<p>I need help</p>'],
                ['name' => 'case[channel]', 'contents' => 'chh200'],
                ['name' => 'case[user_email]', 'contents' => 'example@example.com'],
            ],
        ];

        yield 'multipart phone' => [
            'payload' => new StoreCasePayload(
                case: new CaseStoreData(
                    userCustomId: '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    subject: 'Subject case',
                    content: 'I need help',
                    contentHtml: '<p>I need help</p>',
                    channel: 'chh200',
                    userPhone: '+79998887755',
                )
            ),

            'expected' => [
                ['name' => 'case[user_custom_id]', 'contents' => '8e334869-a6ca-41da-b5cd-a8a51f99a529'],
                ['name' => 'case[subject]', 'contents' => 'Subject case'],
                ['name' => 'case[content]', 'contents' => 'I need help'],
                ['name' => 'case[content_html]', 'contents' => '<p>I need help</p>'],
                ['name' => 'case[channel]', 'contents' => 'chh200'],
                ['name' => 'case[user_phone]', 'contents' => '+79998887755'],
            ],
        ];

        yield 'multipart email phone' => [
            'payload' => new StoreCasePayload(
                case: new CaseStoreData(
                    userCustomId: '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    subject: 'Subject case',
                    content: 'I need help',
                    contentHtml: '<p>I need help</p>',
                    channel: 'chh200',
                    userEmail: 'example@example.com',
                    userPhone: '+79998887755',
                )
            ),

            'expected' => [
                ['name' => 'case[user_custom_id]', 'contents' => '8e334869-a6ca-41da-b5cd-a8a51f99a529'],
                ['name' => 'case[subject]', 'contents' => 'Subject case'],
                ['name' => 'case[content]', 'contents' => 'I need help'],
                ['name' => 'case[content_html]', 'contents' => '<p>I need help</p>'],
                ['name' => 'case[channel]', 'contents' => 'chh200'],
                ['name' => 'case[user_email]', 'contents' => 'example@example.com'],
                ['name' => 'case[user_phone]', 'contents' => '+79998887755'],
            ],
        ];

        yield 'multipart with attachment email phone' => [
            'payload' => new StoreCasePayload(
                case: new CaseStoreData(
                    userCustomId: '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    subject: 'Subject case',
                    content: 'I need help',
                    contentHtml: '<p>I need help</p>',
                    channel: 'chh200',
                    userEmail: 'example@example.com',
                    userPhone: '+79998887755',
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
                ['name' => 'case[user_custom_id]', 'contents' => '8e334869-a6ca-41da-b5cd-a8a51f99a529'],
                ['name' => 'case[subject]', 'contents' => 'Subject case'],
                ['name' => 'case[content]', 'contents' => 'I need help'],
                ['name' => 'case[content_html]', 'contents' => '<p>I need help</p>'],
                ['name' => 'case[channel]', 'contents' => 'chh200'],
                ['name' => 'case[user_email]', 'contents' => 'example@example.com'],
                ['name' => 'case[user_phone]', 'contents' => '+79998887755'],
                [
                    'name' => 'case[attachments][0]',
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
    public function testFromArray(array $data, StoreCasePayload $expected): void
    {
        $actual = StoreCasePayload::validateAndCreate($data);
        $this->assertSame($expected->toArray(), $actual->toArray());
    }

    #[DataProvider('dataMultipartProviderSuccess')]
    public function testToMultipartSuccess(StoreCasePayload $payload, array $expected): void
    {
        $this->assertSame($expected, $payload->toMultipart());
    }
}
