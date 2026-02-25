<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreCase\Payload as StoreCasePayload;
use Palach\Omnidesk\UseCases\V1\StoreCase\Response as StoreCaseResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class StoreCaseTest extends AbstractTestCase
{
    private const string API_URL_CASES = '/api/cases.json';

    public static function dataArrayProvider(): iterable
    {
        yield [
            'payload' => [
                'case' => [
                    'user_email' => 'example@example.com',
                    'user_phone' => '+79998887755',
                    'user_custom_id' => '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    'subject' => 'Subject case',
                    'content' => 'I need help',
                    'content_html' => '<p>I need help</p>',
                    'channel' => 'chh200',
                ],
            ],
            'response' => [
                'case' => [
                    'case_id' => 2000,
                    'case_number' => '664-245651',
                    'subject' => 'Договор и счёт',
                    'user_id' => 123,
                    'staff_id' => 321,
                    'group_id' => 444,
                    'status' => 'waiting',
                    'priority' => 'normal',
                    'channel' => 'chh21',
                    'deleted' => false,
                    'spam' => false,
                ],
            ],
        ];
        yield [
            'payload' => [
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
            'response' => [
                'case' => [
                    'case_id' => 2000,
                    'case_number' => '664-245651',
                    'subject' => 'Договор и счёт',
                    'user_id' => 123,
                    'staff_id' => 321,
                    'group_id' => 444,
                    'status' => 'waiting',
                    'priority' => 'normal',
                    'channel' => 'chh21',
                    'deleted' => false,
                    'spam' => false,
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
                'case' => [
                    'user_email' => 'example@example.com',
                    'user_phone' => '+79998887755',
                    'user_custom_id' => '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    'subject' => 'Subject case',
                    'content' => 'I need help',
                    'content_html' => '<p>I need help</p>',
                    'channel' => 'chh200',
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
                'case' => [
                    'case_id' => 2000,
                    'case_number' => '664-245651',
                    'subject' => 'Договор и счёт',
                    'user_id' => 123,
                    'staff_id' => 321,
                    'group_id' => 444,
                    'status' => 'waiting',
                    'priority' => 'normal',
                    'channel' => 'chh21',
                    'deleted' => false,
                    'spam' => false,
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
        $url = $this->host.self::API_URL_CASES;

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->cases()->store(StoreCasePayload::from($payload));

        $payload = StoreCasePayload::from($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_POST
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(StoreCaseResponse::from($response), $responseData);
    }

    #[DataProvider('dataMultipartProvider')]
    public function testHttpMultipart(array $payload, array $response): void
    {
        $payloadDto = StoreCasePayload::from($payload);

        $url = $this->host.self::API_URL_CASES;

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->cases()->store($payloadDto);

        Http::assertSent(function (Request $request) use ($url) {
            if ($request->url() !== $url || $request->method() !== SymfonyRequest::METHOD_POST || $request->isJson()) {
                return false;
            }

            $contentType = $request->header('Content-Type')[0] ?? '';
            $this->assertStringContainsString('multipart/form-data', $contentType);

            $body = $request->body();

            $this->assertStringContainsString('name="case[user_custom_id]"', $body);
            $this->assertStringContainsString('8e334869-a6ca-41da-b5cd-a8a51f99a529', $body);

            $this->assertStringContainsString('name="case[subject]"', $body);
            $this->assertStringContainsString('Subject case', $body);

            $this->assertStringContainsString('name="case[user_email]"', $body);
            $this->assertStringContainsString('example@example.com', $body);

            $this->assertStringContainsString('name="case[user_phone]"', $body);
            $this->assertStringContainsString('+79998887755', $body);

            $this->assertStringContainsString(
                'name="case[attachments][0]"',
                $body
            );

            $this->assertStringContainsString('file.txt', $body);
            $this->assertStringContainsString('file-content', $body);

            return true;
        });

        $this->assertEquals(
            StoreCaseResponse::from($response),
            $responseData
        );
    }
}
