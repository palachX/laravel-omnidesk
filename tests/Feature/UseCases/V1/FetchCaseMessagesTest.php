<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\MessageData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCaseMessages\Payload as FetchCaseMessagesPayload;
use Palach\Omnidesk\UseCases\V1\FetchCaseMessages\Response as FetchCaseMessagesResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchCaseMessagesTest extends AbstractTestCase
{
    private const string API_URL_CASE_MESSAGES = '/api/cases/%d/messages.json';

    public static function dataProvider(): iterable
    {
        yield 'full data with attachments and ratings' => [
            'payload' => [
                'case_id' => 2000,
                'page' => 1,
                'limit' => 10,
                'order' => 'asc',
            ],
            'response' => [
                '0' => [
                    'message' => [
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
                ],
                '1' => [
                    'message' => [
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
        ];

        yield 'minimal data without optional parameters' => [
            'payload' => [
                'case_id' => 2001,
            ],
            'response' => [
                '0' => [
                    'message' => [
                        'message_id' => 2001,
                        'user_id' => 456,
                        'staff_id' => 0,
                        'content' => 'Simple user message',
                        'attachments' => [],
                        'note' => false,
                        'sent_via_rule' => false,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    ],
                ],
                'total_count' => 1,
            ],
        ];

        yield 'data with delayed sending' => [
            'payload' => [
                'case_id' => 2002,
                'page' => 2,
                'order' => 'desc',
            ],
            'response' => [
                '0' => [
                    'message' => [
                        'message_id' => 2002,
                        'user_id' => 0,
                        'staff_id' => 789,
                        'content' => 'Scheduled message',
                        'content_html' => 'Scheduled message content',
                        'attachments' => [],
                        'note' => false,
                        'sent_via_rule' => false,
                        'created_at' => 'Mon, 15 May 2023 09:28:43 +0300',
                        'sent_at' => 'Mon, 15 May 2023 10:15:17 +0300',
                    ],
                ],
                'total_count' => 1,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = FetchCaseMessagesPayload::from($payload);
        $caseId = $payload->caseId;

        $url = sprintf(self::API_URL_CASE_MESSAGES, $caseId);
        $query = http_build_query($payload->toQuery());
        $fullUrl = $this->host.$url.($query ? '?'.$query : '');

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $messages = $this->makeHttpClient()->messages()->fetchMessages($payload);

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        /**
         * @var array<int, array{message: array<string, mixed>}> $messagesRaw
         */
        $messagesRaw = array_values($response);

        $messageCollection = collect($messagesRaw)
            ->map(function (array $item) {
                return MessageData::from($item['message']);
            });

        $this->assertEquals(new FetchCaseMessagesResponse(
            messages: $messageCollection,
            totalCount: $total
        ), $messages);
    }
}
