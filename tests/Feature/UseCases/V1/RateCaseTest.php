<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\RateCase\Payload as RateCasePayload;
use Palach\Omnidesk\UseCases\V1\RateCase\Response as RateCaseResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class RateCaseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield [
            'payload' => [
                'case_id' => 123,
                'rate' => [
                    'rating' => 'high',
                    'rating_comment' => 'cool',
                    'rated_staff_id' => 189,
                ],
            ],
            'response' => [
                'case' => [
                    'case_id' => 2000,
                    'case_number' => '664-245651',
                    'subject' => 'I need help',
                    'user_id' => 123,
                    'staff_id' => 22,
                    'group_id' => 44,
                    'status' => 'waiting',
                    'priority' => 'normal',
                    'channel' => 'web',
                    'recipient' => 'user@domain.ru',
                    'cc_emails' => 'user_cc@domain.ru,user_cc2@domain.ru',
                    'bcc_emails' => 'user_bcc@domain.ru',
                    'deleted' => false,
                    'spam' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    'language_id' => 2,
                    'custom_fields' => [
                        'cf_25' => 'some text',
                        'cf_30' => 'another field',
                    ],
                    'labels' => [101, 102],
                    'rating' => 'high',
                    'rating_comment' => 'cool123',
                    'rated_staff_id' => 189,
                ],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = RateCasePayload::from($payload);
        $caseId = $payload->caseId;

        $url = $this->host."/api/cases/$caseId/rate.json";

        Http::fake([
            $url => Http::response($response, 200),
        ]);

        $actual = $this->makeHttpClient()->cases()->rateCase($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode($payload->toArray());
        });

        $expected = RateCaseResponse::from($response);

        $this->assertEquals($expected, $actual);
    }
}
