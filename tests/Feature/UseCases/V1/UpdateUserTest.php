<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateUser\Payload as UpdateUserPayload;
use Palach\Omnidesk\UseCases\V1\UpdateUser\Response as UpdateUserResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class UpdateUserTest extends AbstractTestCase
{
    private const string API_URL_USERS = '/api/users.json';

    public static function dataArrayProvider(): iterable
    {
        yield [
            'userId' => 200,
            'payload' => [
                'user' => [
                    'user_full_name' => "User's full name changed",
                    'language_id' => 1,
                    'custom_fields' => [
                        'cf_20' => 'some data',
                        'cf_23' => true,
                    ],
                ],
            ],
            'response' => [
                'user' => [
                    'user_id' => 200,
                    'user_full_name' => "User's full name changed",
                    'company_name' => "User's company name",
                    'company_position' => "User's position",
                    'thumbnail' => '',
                    'confirmed' => false,
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    'type' => 'email',
                    'user_email' => 'user@domain.ru',
                    'language_id' => 1,
                    'custom_fields' => [
                        'cf_20' => 'some data',
                        'cf_23' => true,
                    ],
                ],
            ],
        ];
        yield [
            'userId' => 201,
            'payload' => [
                'user' => [
                    'user_email' => 'newemail@domain.ru',
                    'user_full_name' => 'John Doe Updated',
                    'company_name' => 'Updated Company',
                    'company_position' => 'Senior Developer',
                    'user_note' => 'Updated note',
                    'language_id' => 2,
                ],
            ],
            'response' => [
                'user' => [
                    'user_id' => 201,
                    'user_full_name' => 'John Doe Updated',
                    'company_name' => 'Updated Company',
                    'company_position' => 'Senior Developer',
                    'thumbnail' => '',
                    'confirmed' => false,
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Wed, 15 Jun 2023 14:30:00 +0300',
                    'updated_at' => 'Thu, 25 Dec 2014 15:30:00 +0200',
                    'type' => 'email',
                    'user_email' => 'newemail@domain.ru',
                    'language_id' => 2,
                    'user_note' => 'Updated note',
                ],
            ],
        ];
        yield [
            'userId' => 202,
            'payload' => [
                'user' => [
                    'user_phone' => '+79269876543',
                    'user_full_name' => 'Jane Smith Updated',
                    'company_name' => 'Tech Company Inc',
                    'company_position' => 'Senior Manager',
                    'user_note' => 'VIP client updated',
                ],
            ],
            'response' => [
                'user' => [
                    'user_id' => 202,
                    'user_full_name' => 'Jane Smith Updated',
                    'company_name' => 'Tech Company Inc',
                    'company_position' => 'Senior Manager',
                    'thumbnail' => '',
                    'confirmed' => true,
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Fri, 26 Dec 2014 11:20:00 +0200',
                    'type' => 'phone',
                    'user_phone' => '+79269876543',
                    'language_id' => 1,
                    'user_note' => 'VIP client updated',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $userId, array $payload, array $response): void
    {
        $url = $this->host.str_replace('.json', "/{$userId}.json", self::API_URL_USERS);

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->users()->update($userId, UpdateUserPayload::from($payload));

        $payload = UpdateUserPayload::from($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(UpdateUserResponse::from($response), $responseData);
    }
}
