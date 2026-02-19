<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\RateMessage\Payload;
use Palach\Omnidesk\UseCases\V1\RateMessage\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class RateMessageTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield [
            'payload' => [
                'case_id' => 123,
                'message_id' => 2001,
                'rate' => [
                    'rating' => 'low',
                    'rating_comment' => 'cool',
                ],
            ],
            'response' => [
                'message' => [
                    'message_id' => 2001,
                    'user_id' => 0,
                    'staff_id' => 123,
                    'content' => 'I need help',
                    'content_html' => '',
                    'attachments' => [],
                    'note' => false,
                    'created_at' => 'Mon, 06 May 2014 00:15:17 +0300',
                    'rating' => 'low',
                    'rating_comment' => 'cool',
                    'rated_staff_id' => 0,
                ],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = Payload::from($payload);
        $caseId = $payload->caseId;
        $messageId = $payload->messageId;

        $url = $this->host."/api/cases/$caseId/rate/$messageId.json";

        Http::fake([
            $url => Http::response($response, 200),
        ]);

        $actual = $this->makeHttpClient()->messages()->rate($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode($payload->toArray());
        });

        $expected = Response::from($response);

        $this->assertEquals($expected, $actual);
    }
}
