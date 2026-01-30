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

    public static function dataProvider(): iterable
    {
        yield [
            'payload' => [
                'case' => [
                    'user_email' => 'example@example.com',
                    'user_phone' => '+79998887755',
                    'user_custom_id' => '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    'subject' => 'Subject case',
                    'content' => 'I need help',
                    'content_html' => 'I need help',
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
    }

    #[DataProvider('dataProvider')]
    public function testHttp($payload, array $response): void
    {
        Http::fake([
            "$this->host".self::API_URL_CASES => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->storeCase(StoreCasePayload::from($payload));

        $payload = StoreCasePayload::from($payload);

        Http::assertSent(function (Request $request) use ($payload) {

            $this->assertEquals($payload->toArray(), $request->data());
            $this->assertTrue($request->isJson());

            return $request->url() === "{$this->host}".self::API_URL_CASES
                && $request->method() === SymfonyRequest::METHOD_POST;
        });

        $this->assertEquals(StoreCaseResponse::from($response), $responseData);
    }
}
