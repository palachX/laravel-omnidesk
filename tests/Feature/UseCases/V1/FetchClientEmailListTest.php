<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\ClientEmailData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchClientEmailList\Response as FetchClientEmailListResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchClientEmailListTest extends AbstractTestCase
{
    private const string API_URL_CLIENT_EMAILS = '/api/client_emails.json';

    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'response' => [
                '0' => [
                    'client_emails' => [
                        'email_id' => 7,
                        'email' => 'example@gmail.com',
                        'active' => true,
                    ],
                ],
                '1' => [
                    'client_emails' => [
                        'email_id' => 9,
                        'email' => 'support@example.omnidesk.ru',
                        'active' => true,
                    ],
                ],
                '2' => [
                    'client_emails' => [
                        'email_id' => 127,
                        'email' => 'test@example.omnidesk.ru',
                        'active' => false,
                    ],
                ],
                'total_count' => 3,
            ],
        ];
        yield 'no pagination' => [
            'response' => [
                '0' => [
                    'client_emails' => [
                        'email_id' => 1,
                        'email' => 'single@example.com',
                        'active' => true,
                    ],
                ],
                'total_count' => 1,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $response): void
    {
        $url = self::API_URL_CLIENT_EMAILS;
        $fullUrl = $this->host.$url;

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->clientEmails()->fetchList();

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $totalCount = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        /**
         * @var array<int, array{client_emails: array<string, mixed>}> $clientEmailsRaw
         */
        $clientEmailsRaw = array_values($response);

        $clientEmails = collect($clientEmailsRaw)
            ->map(function (array $item) {
                return ClientEmailData::from($item['client_emails']);
            });

        $this->assertEquals(new FetchClientEmailListResponse(
            clientEmails: $clientEmails,
            totalCount: $totalCount
        ), $list);
    }
}
