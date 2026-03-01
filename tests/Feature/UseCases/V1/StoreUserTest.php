<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreUser\Payload as StoreUserPayload;
use Palach\Omnidesk\UseCases\V1\StoreUser\Response as StoreUserResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class StoreUserTest extends AbstractTestCase
{
    private const string API_URL_USERS = '/api/users.json';

    public static function dataArrayProvider(): iterable
    {
        yield [
            'payload' => [
                'user' => [
                    'user_email' => 'user@domain.ru',
                    'user_full_name' => "User's full name",
                    'company_name' => "User's company name",
                    'company_position' => "User's position",
                    'user_note' => 'Some note',
                    'language_id' => 2,
                    'custom_fields' => [
                        'cf_20' => 'some data',
                        'cf_23' => true,
                    ],
                ],
            ],
            'response' => [
                'user' => [
                    'user_id' => 200,
                    'user_full_name' => "User's full name",
                    'company_name' => "User's company name",
                    'company_position' => "User's position",
                    'thumbnail' => '',
                    'confirmed' => false,
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    'password' => 'bsdegs',
                    'type' => 'email',
                    'user_email' => 'user@domain.ru',
                    'language_id' => 2,
                    'custom_fields' => [
                        'cf_20' => 'some data',
                        'cf_23' => true,
                    ],
                    'linked_users' => [123, 456],
                ],
            ],
        ];
        yield [
            'payload' => [
                'user' => [
                    'user_whatsapp_phone' => '+79261234567',
                    'user_full_name' => 'John Doe',
                    'company_name' => 'Example Corp',
                    'company_position' => 'Developer',
                    'user_note' => 'VIP customer',
                    'language_id' => 1,
                ],
            ],
            'response' => [
                'user' => [
                    'user_id' => 201,
                    'user_full_name' => 'John Doe',
                    'company_name' => 'Example Corp',
                    'company_position' => 'Developer',
                    'thumbnail' => '',
                    'confirmed' => true,
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Wed, 15 Jun 2023 14:30:00 +0300',
                    'updated_at' => 'Wed, 15 Jun 2023 14:30:00 +0300',
                    'password' => 'randompass',
                    'type' => 'whatsapp',
                    'user_whatsapp_phone' => '+79261234567',
                    'language_id' => 1,
                    'linked_users' => [],
                ],
            ],
        ];
        yield [
            'payload' => [
                'user' => [
                    'user_telegram' => '123456789',
                    'user_full_name' => 'Jane Smith',
                    'company_name' => 'Tech Company',
                    'company_position' => 'Manager',
                    'user_note' => 'Important client',
                    'language_id' => 2,
                ],
            ],
            'response' => [
                'user' => [
                    'user_id' => 202,
                    'user_full_name' => 'Jane Smith',
                    'company_name' => 'Tech Company',
                    'company_position' => 'Manager',
                    'thumbnail' => '',
                    'confirmed' => false,
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'password' => 'securepass',
                    'type' => 'telegram',
                    'user_telegram' => '123456789',
                    'language_id' => 2,
                    'linked_users' => [456],
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $url = $this->host.self::API_URL_USERS;

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->users()->store(StoreUserPayload::from($payload));

        $payload = StoreUserPayload::from($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_POST
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(StoreUserResponse::from($response), $responseData);
    }
}
