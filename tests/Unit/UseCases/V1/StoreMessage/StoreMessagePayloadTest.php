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
        yield 'full data id' => [
            'data' => [
                'message' => [
                    'case_id' => 123,
                    'content' => 'I need help!',
                    'user_id' => 321,
                    'attachment_urls' => [
                        'https://abcompany.ru/548899/contract.pdf',
                        'https://abcompany.ru/548899/invoice.pdf',
                    ],
                ],
            ],

            'expected' => new StoreMessagePayload(
                message: new MessageStoreData(
                    userId: 321,
                    content: 'I need help!',
                    caseId: 123,
                    attachmentUrls: [
                        'https://abcompany.ru/548899/contract.pdf',
                        'https://abcompany.ru/548899/invoice.pdf',
                    ]
                )
            ),
        ];
        yield 'full data number' => [
            'data' => [
                'message' => [
                    'case_number' => '664-245651',
                    'content' => 'I need help!',
                    'user_id' => 321,
                    'attachment_urls' => [
                        'https://abcompany.ru/548899/contract.pdf',
                        'https://abcompany.ru/548899/invoice.pdf',
                    ],
                ],
            ],

            'expected' => new StoreMessagePayload(
                message: new MessageStoreData(
                    userId: 321,
                    content: 'I need help!',
                    caseNumber: '664-245651',
                    attachmentUrls: [
                        'https://abcompany.ru/548899/contract.pdf',
                        'https://abcompany.ru/548899/invoice.pdf',
                    ]
                )
            ),
        ];
        yield 'required data id' => [
            'data' => [
                'message' => [
                    'case_id' => 123,
                    'content' => 'I need help!',
                    'user_id' => 321,
                ],
            ],

            'expected' => new StoreMessagePayload(
                message: new MessageStoreData(
                    userId: 321,
                    content: 'I need help!',
                    caseId: 123,
                )
            ),
        ];
        yield 'required data number' => [
            'data' => [
                'message' => [
                    'case_number' => '664-245651',
                    'content' => 'I need help!',
                    'user_id' => 321,
                ],
            ],

            'expected' => new StoreMessagePayload(
                message: new MessageStoreData(
                    userId: 321,
                    content: 'I need help!',
                    caseNumber: '664-245651',
                )
            ),
        ];
        yield 'required data number with attachments' => [
            'data' => [
                'message' => [
                    'case_number' => '664-245651',
                    'content' => 'I need help!',
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
                message: new MessageStoreData(
                    userId: 321,
                    content: 'I need help!',
                    caseNumber: '664-245651',
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
        yield 'without attachments case_id' => [
            'payload' => new StoreMessagePayload(
                message: new MessageStoreData(
                    userId: 321,
                    content: 'I need help!',
                    caseId: 123,
                )
            ),

            'expected' => [
                [
                    'name' => 'message[user_id]',
                    'contents' => '321',
                ],
                [
                    'name' => 'message[content]',
                    'contents' => 'I need help!',
                ],
                [
                    'name' => 'message[case_id]',
                    'contents' => '123',
                ],
            ],
        ];

        yield 'with attachment and case_number' => [
            'payload' => new StoreMessagePayload(
                message: new MessageStoreData(
                    userId: 321,
                    content: 'I need help!',
                    caseNumber: '664-245651',
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
                    'name' => 'message[user_id]',
                    'contents' => '321',
                ],
                [
                    'name' => 'message[content]',
                    'contents' => 'I need help!',
                ],
                [
                    'name' => 'message[case_number]',
                    'contents' => '664-245651',
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
