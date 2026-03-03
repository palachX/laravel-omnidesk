<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchUser\Payload as FetchUserPayload;
use Palach\Omnidesk\UseCases\V1\FetchUser\Response as FetchUserResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchUserTest extends AbstractTestCase
{
    private const string API_URL_USERS = '/api/users';

    public static function dataProvider(): iterable
    {
        yield [
            'userId' => 200,
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
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0300',
                    'last_contact_at' => 'Tue, 27 Dec 2014 11:52:09 +0300',
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
            'userId' => 201,
            'response' => [
                'user' => [
                    'user_id' => 201,
                    'user_full_name' => 'John Doe',
                    'company_name' => 'Acme Inc',
                    'company_position' => 'Developer',
                    'thumbnail' => 'https://example.com/avatar.jpg',
                    'confirmed' => true,
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Wed, 15 Jan 2020 10:30:00 +0300',
                    'updated_at' => 'Fri, 20 Dec 2024 15:45:30 +0300',
                    'type' => 'email',
                    'user_email' => 'john.doe@example.com',
                    'language_id' => 1,
                ],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(int $userId, array $response): void
    {
        $url = $this->host.self::API_URL_USERS."/{$userId}.json";

        Http::fake([
            $url => Http::response($response),
        ]);

        $payload = new FetchUserPayload(
            user: new \Palach\Omnidesk\UseCases\V1\FetchUser\UserFetchData(
                userId: $userId
            )
        );

        $responseData = $this->makeHttpClient()->users()->fetch($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $this->assertEquals(FetchUserResponse::from($response), $responseData);
    }
}
