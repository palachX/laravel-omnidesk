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
                'message' => [
                    'case_id' => 123,
                    'message_id' => 2000,
                    'content' => 'I need help',
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
                    'message_id' => 911,
                    'content' => 'I need help!',
                ],
            ],
            'response' => [
                'message' => [
                    'message_id' => 911,
                    'user_id' => 123,
                    'staff_id' => 321,
                    'content' => 'I need help!',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = UpdateMessagePayload::from($payload);
        $caseId = $payload->message->caseId;
        $messageId = $payload->message->messageId;

        $url = "/api/cases/$caseId/messages/$messageId.json";

        Http::fake([
            "$this->host".$url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->messages()->update(UpdateMessagePayload::from($payload));

        Http::assertSent(function (Request $request) use ($payload, $url) {

            $this->assertEquals($payload->toArray(), $request->data());
            $this->assertTrue($request->isJson());

            return $request->url() === "{$this->host}".$url
                && $request->method() === SymfonyRequest::METHOD_POST;
        });

        $this->assertEquals(UpdateMessageResponse::from($response), $responseData);
    }
}
