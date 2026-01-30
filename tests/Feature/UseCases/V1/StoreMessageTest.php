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
    public static function dataProvider(): iterable
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
    }

    #[DataProvider('dataProvider')]
    public function testHttp($payload, array $response): void
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
}
