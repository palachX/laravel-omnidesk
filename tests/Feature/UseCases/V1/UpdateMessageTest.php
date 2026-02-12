<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateMessage\Payload as UpdateMessagePayload;
use Palach\Omnidesk\UseCases\V1\UpdateMessage\Response as UpdateMessageResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class UpdateMessageTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield [
            'payload' => [
                'case_id' => 123,
                'message_id' => 2000,
                'message' => [
                    'content' => 'I need help',
                    'content_html' => '<p>I need help!</p>',
                ],
            ],
            'response' => [
                'message' => [
                    'message_id' => 2000,
                    'user_id' => 123,
                    'staff_id' => 321,
                    'content' => 'I need help',
                    'content_html' => '<p>I need help!</p>',
                    'note' => false,
                    'created_at' => 'Mon, 06 May 2014 00:15:17 +0300',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = UpdateMessagePayload::from($payload);
        $caseId = $payload->caseId;
        $messageId = $payload->messageId;

        $url = $this->host."/api/cases/$caseId/messages/$messageId.json";

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->messages()->update(UpdateMessagePayload::from($payload));

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->method() === SymfonyRequest::METHOD_POST
                && $request->isJson()
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(UpdateMessageResponse::from($response), $responseData);
    }
}
