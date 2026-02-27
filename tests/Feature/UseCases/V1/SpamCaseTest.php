<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\SpamCase\Payload as SpamCasePayload;
use Palach\Omnidesk\UseCases\V1\SpamCase\Response as SpamCaseResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class SpamCaseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield [
            'payload' => ['case_id' => 2000],
            'response' => [
                'case' => [
                    'case_id' => 2000,
                    'case_number' => '664-245651',
                    'subject' => 'Test subject changed',
                    'user_id' => 123,
                    'staff_id' => 22,
                    'group_id' => 44,
                    'status' => 'closed',
                    'priority' => 'critical',
                    'channel' => 'web',
                    'recipient' => 'user@domain.ru',
                    'deleted' => false,
                    'spam' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    'language_id' => 2,
                    'custom_fields' => [
                        'cf_25' => 'some text',
                        'cf_30' => 'another field',
                    ],
                    'labels' => [101, 102],
                ],
            ],
        ];

        yield [
            'payload' => ['case_id' => 12345],
            'response' => [
                'case' => [
                    'case_id' => 12345,
                    'case_number' => '123-456789',
                    'subject' => 'Another test case',
                    'user_id' => 456,
                    'staff_id' => 78,
                    'group_id' => 90,
                    'status' => 'open',
                    'priority' => 'normal',
                    'channel' => 'email',
                    'deleted' => false,
                    'spam' => true,
                    'created_at' => 'Wed, 15 Jan 2020 12:30:00 +0300',
                    'updated_at' => 'Wed, 15 Jan 2020 12:35:00 +0300',
                ],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = SpamCasePayload::from($payload);
        $caseId = $payload->caseId;

        $url = $this->host."/api/cases/$caseId/spam.json";

        Http::fake([
            $url => Http::response($response, 200),
        ]);

        $actual = $this->makeHttpClient()->cases()->spamCase($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode([]);
        });

        $this->assertEquals(SpamCaseResponse::from($response), $actual);
    }
}
