<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreNote\Payload as StoreNotePayload;
use Palach\Omnidesk\UseCases\V1\StoreNote\Response as StoreNoteResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class StoreNoteTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield [
            'payload' => [
                'case_id' => 123,
                'note' => [
                    'content' => 'This is a note',
                    'content_html' => '<p>This is a note</p>',
                    'created_at' => 1714977317,
                ],
            ],
            'response' => [
                'message' => [
                    'message_id' => 2000,
                    'user_id' => 123,
                    'staff_id' => 321,
                    'content' => 'This is a note',
                    'content_html' => '<p>This is a note</p>',
                    'note' => true,
                    'created_at' => 'Mon, 06 May 2014 00:15:17 +0300',
                ],
            ],
        ];
        yield [
            'payload' => [
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
            'response' => [
                'message' => [
                    'message_id' => 2000,
                    'user_id' => 123,
                    'staff_id' => 321,
                    'content' => 'This is a note',
                    'content_html' => '<p>This is a note</p>',
                    'note' => true,
                    'created_at' => 'Mon, 06 May 2014 00:15:17 +0300',
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
                ],
            ],
        ];
    }

    public static function dataMultipartProvider(): iterable
    {
        yield [
            'payload' => [
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
            'response' => [
                'message' => [
                    'message_id' => 2000,
                    'user_id' => 123,
                    'staff_id' => 321,
                    'content' => 'This is a note',
                    'content_html' => '<p>This is a note</p>',
                    'note' => true,
                    'created_at' => 'Mon, 06 May 2014 00:15:17 +0300',
                    'attachments' => [
                        [
                            'file_id' => 345,
                            'file_name' => 'contract.pdf',
                            'file_size' => 40863,
                            'mime_type' => 'application/pdf',
                            'url' => 'https://[domain].omnidesk.ru/some_path_here/345',
                        ],
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = StoreNotePayload::from($payload);
        $caseId = $payload->caseId;

        $url = $this->host."/api/cases/$caseId/note.json";

        Http::fake([
            $url => Http::response($response, 200),
        ]);

        $actual = $this->makeHttpClient()->notes()->store($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_POST
                && $request->body() === json_encode($payload->toArray());
        });

        $expected = StoreNoteResponse::from($response);

        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('dataMultipartProvider')]
    public function testHttpMultipart(array $payload, array $response): void
    {
        $payloadDto = StoreNotePayload::from($payload);
        $caseId = $payloadDto->caseId;

        $url = $this->host."/api/cases/$caseId/note.json";

        Http::fake([
            $url => Http::response($response, 200),
        ]);

        $actual = $this->makeHttpClient()->notes()->store($payloadDto);

        Http::assertSent(function (Request $request) use ($url) {
            if ($request->url() !== $url || $request->method() !== SymfonyRequest::METHOD_POST || $request->isJson()) {
                return false;
            }

            $body = $request->body();

            $this->assertStringContainsString(
                'name="note[content]"',
                $body
            );
            $this->assertStringContainsString('This is a note', $body);

            $this->assertStringContainsString(
                'name="note[content_html]"',
                $body
            );
            $this->assertStringContainsString('<p>This is a note</p>', $body);

            $this->assertStringContainsString(
                'name="note[attachments][0]"',
                $body
            );
            $this->assertStringContainsString('file-content', $body);

            return true;
        });

        $expected = StoreNoteResponse::from($response);

        $this->assertEquals($expected, $actual);
    }
}
