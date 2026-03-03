<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchUserIdentification\Payload as FetchUserIdentificationPayload;
use Palach\Omnidesk\UseCases\V1\FetchUserIdentification\Response as FetchUserIdentificationResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchUserIdentificationTest extends AbstractTestCase
{
    private const string API_URL_IDENTIFICATION = '/api/users/identification.json';

    public static function dataProvider(): iterable
    {
        yield [
            'payload' => [
                'user' => [
                    'user_full_name' => 'Семёнов Алексей',
                    'company_name' => 'ABCompany',
                    'user_email' => 'a.semenov@abcompany.com',
                    'user_phone' => '+79221110000',
                    'user_whatsapp_phone' => '+79221110000',
                    'user_custom_id' => 'a.semenov',
                    'user_custom_channel' => '481',
                    'custom_fields' => [
                        'cf_7264' => 'some data',
                        'cf_7786' => 2,
                        'cf_7486' => true,
                    ],
                ],
            ],
            'response' => [
                'code' => 'o_37BD49_uv',
            ],
        ];
        yield [
            'payload' => [
                'user' => [
                    'user_full_name' => 'John Doe',
                    'company_name' => 'Example Corp',
                    'user_email' => 'john.doe@example.com',
                    'user_phone' => '+1234567890',
                    'company_position' => 'Developer',
                    'user_note' => 'VIP customer',
                    'language_id' => 1,
                ],
            ],
            'response' => [
                'code' => 'o_ABC123_xy',
            ],
        ];
        yield [
            'payload' => [
                'user' => [
                    'user_telegram_data' => 'john_doe_telegram',
                    'user_full_name' => 'John Smith',
                    'company_name' => 'Tech Company',
                ],
            ],
            'response' => [
                'code' => 'o_DEF456_zz',
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $url = $this->host.self::API_URL_IDENTIFICATION;

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->users()->fetchUserIdentification(FetchUserIdentificationPayload::from($payload));

        $payload = FetchUserIdentificationPayload::from($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_POST
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(FetchUserIdentificationResponse::from($response), $responseData);
    }
}
