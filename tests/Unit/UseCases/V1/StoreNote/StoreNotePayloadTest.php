<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreNote;

use Palach\Omnidesk\DTO\AttachmentData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreNote\NoteStoreData;
use Palach\Omnidesk\UseCases\V1\StoreNote\Payload as StoreNotePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreNotePayloadTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'case_id' => 123,
                'note' => [
                    'content' => 'This is a note',
                    'content_html' => '<p>This is a note</p>',
                    'created_at' => 1714977317,
                    'attachment_urls' => [
                        'https://abcompany.ru/548899/contract.pdf',
                        'https://abcompany.ru/548899/invoice.pdf',
                    ],
                ],
            ],

            'expected' => new StoreNotePayload(
                caseId: 123,
                note: new NoteStoreData(
                    content: 'This is a note',
                    contentHtml: '<p>This is a note</p>',
                    createdAt: 1714977317,
                    attachmentUrls: [
                        'https://abcompany.ru/548899/contract.pdf',
                        'https://abcompany.ru/548899/invoice.pdf',
                    ]
                )
            ),
        ];

        yield 'required data' => [
            'data' => [
                'case_id' => 123,
                'note' => [
                    'content' => 'This is a note',
                    'content_html' => '<p>This is a note</p>',
                    'created_at' => 1714977317,
                ],
            ],

            'expected' => new StoreNotePayload(
                caseId: 123,
                note: new NoteStoreData(
                    content: 'This is a note',
                    contentHtml: '<p>This is a note</p>',
                    createdAt: 1714977317,
                )
            ),
        ];

        yield 'required data with attachments' => [
            'data' => [
                'case_id' => 123,
                'note' => [
                    'content' => 'This is a note',
                    'content_html' => '<p>This is a note</p>',
                    'created_at' => 1714977317,
                    'attachments' => [
                        [
                            'name' => 'file.txt',
                            'mime_type' => 'text/plain',
                            'content' => 'file-content',
                        ],
                    ],
                ],
            ],

            'expected' => new StoreNotePayload(
                caseId: 123,
                note: new NoteStoreData(
                    content: 'This is a note',
                    contentHtml: '<p>This is a note</p>',
                    createdAt: 1714977317,
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
            'payload' => new StoreNotePayload(
                caseId: 123,
                note: new NoteStoreData(
                    content: 'This is a note',
                    contentHtml: '<p>This is a note</p>',
                    createdAt: 1714977317,
                )
            ),

            'expected' => [
                [
                    'name' => 'note[content]',
                    'contents' => 'This is a note',
                ],
                [
                    'name' => 'note[content_html]',
                    'contents' => '<p>This is a note</p>',
                ],
                [
                    'name' => 'note[created_at]',
                    'contents' => '1714977317',
                ],
            ],
        ];

        yield 'with attachment' => [
            'payload' => new StoreNotePayload(
                caseId: 123,
                note: new NoteStoreData(
                    content: 'This is a note',
                    contentHtml: '<p>This is a note</p>',
                    createdAt: 1714977317,
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
                    'name' => 'note[content]',
                    'contents' => 'This is a note',
                ],
                [
                    'name' => 'note[content_html]',
                    'contents' => '<p>This is a note</p>',
                ],
                [
                    'name' => 'note[created_at]',
                    'contents' => '1714977317',
                ],
                [
                    'name' => 'note[attachments][0]',
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
    public function testFromArray(array $data, StoreNotePayload $expected): void
    {
        $actual = StoreNotePayload::validateAndCreate($data);
        $this->assertEquals($expected->toArray(), $actual->toArray());
    }

    #[DataProvider('dataMultipartProvider')]
    public function testToMultipart(StoreNotePayload $payload, array $expected): void
    {
        $this->assertSame($expected, $payload->toMultipart());
    }
}
