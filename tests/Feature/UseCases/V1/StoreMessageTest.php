<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreMessage\Payload as StoreMessagePayload;
use Palach\Omnidesk\UseCases\V1\StoreMessage\Response as StoreMessageResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class StoreMessageTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield [
            'payload' => [
                'message' => [
                    'case_id' => 123,
                    'content' => 'I need help!',
                    'user_id' => 321,
                ],
            ],
            'response' => [
                'message' => [
                    'message_id' => 2000,
                    'user_id' => 123,
                    'staff_id' => 321,
                    'content' => 'I need help',
                ],
            ],
        ];
        yield [
            'payload' => [
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
            'response' => [
                'message' => [
                    'message_id' => 2000,
                    'user_id' => 123,
                    'staff_id' => 321,
                    'content' => 'I need help',
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
                'message' => [
                    'case_id' => 123,
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
            'response' => [
                'message' => [
                    'message_id' => 2000,
                    'user_id' => 123,
                    'staff_id' => 321,
                    'content' => 'I need help',
                    'attachments' => [
                        [
                            'file_id' => 345,
                            'file_name' => 'file.txt',
                            'file_size' => 40863,
                            'mime_type' => 'text/plain',
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
        $payload = StoreMessagePayload::from($payload);
        $caseId = $payload->message->caseId;

        $url = "/api/cases/$caseId/messages.json";

        Http::fake([
            "$this->host".$url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->storeMessage(StoreMessagePayload::from($payload));

        Http::assertSent(function (Request $request) use ($payload, $url) {

            $this->assertEquals($payload->toArray(), $request->data());
            $this->assertTrue($request->isJson());

            return $request->url() === "{$this->host}".$url
                && $request->method() === SymfonyRequest::METHOD_POST;
        });

        $this->assertEquals(StoreMessageResponse::from($response), $responseData);
    }

    #[DataProvider('dataMultipartProvider')]
    public function testHttpMultipart(array $payload, array $response): void
    {
        $payloadDto = StoreMessagePayload::from($payload);
        $caseId = $payloadDto->message->caseId;

        $url = "/api/cases/$caseId/messages.json";

        Http::fake([
            "{$this->host}{$url}" => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->storeMessage($payloadDto);

        Http::assertSent(function (Request $request) use ($url) {
            $this->assertSame("{$this->host}{$url}", $request->url());
            $this->assertSame('POST', $request->method());

            $this->assertFalse($request->isJson());

            $contentType = $request->header('Content-Type')[0] ?? '';
            $this->assertStringContainsString('multipart/form-data', $contentType);

            $body = $request->body();

            $this->assertStringContainsString(
                'name="message[case_id]"',
                $body
            );
            $this->assertStringContainsString('123', $body);

            $this->assertStringContainsString(
                'name="message[user_id]"',
                $body
            );
            $this->assertStringContainsString('321', $body);

            $this->assertStringContainsString(
                'name="message[content]"',
                $body
            );
            $this->assertStringContainsString('I need help!', $body);

            $this->assertStringContainsString(
                'name="message[attachments][0]"',
                $body
            );

            $this->assertStringContainsString('file.txt', $body);
            $this->assertStringContainsString('file-content', $body);
            $this->assertStringContainsString('text/plain', $body);

            return true;
        });

        $this->assertEquals(
            StoreMessageResponse::from($response),
            $responseData
        );
    }
}
