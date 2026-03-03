<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\UserData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchUserList\Payload as FetchUserListPayload;
use Palach\Omnidesk\UseCases\V1\FetchUserList\Response as FetchUserListResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchUserListTest extends AbstractTestCase
{
    private const string API_URL_USERS = '/api/users.json';

    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'payload' => [
                'page' => 1,
                'limit' => 10,
                'user_email' => 'test@example.com',
                'user_phone' => '+1234567890',
                'user_custom_id' => '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                'user_custom_channel' => 'cch101',
                'company_id' => [123, 456],
                'language_id' => 2,
                'custom_fields' => ['cf_20' => 'some data', 'cf_23' => true],
                'amount_of_cases' => true,
                'from_time' => '2023-01-01',
                'to_time' => '2023-12-31',
                'from_updated_time' => '2023-01-01',
                'to_updated_time' => '2023-12-31',
                'from_last_contact_time' => '2023-01-01',
                'to_last_contact_time' => '2023-12-31',
            ],
            'response' => [
                [
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
                    ],
                ],
                [
                    'user' => [
                        'user_id' => 300,
                        'user_full_name' => "Second user's full name",
                        'company_name' => "Second user's company name",
                        'company_position' => "Second user's position",
                        'thumbnail' => '',
                        'confirmed' => false,
                        'active' => true,
                        'deleted' => false,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0300',
                        'last_contact_at' => 'Tue, 27 Dec 2014 11:52:09 +0300',
                        'type' => 'facebook',
                        'facebook_id' => 123123123,
                        'language_id' => 2,
                        'custom_fields' => [
                            'cf_20' => 'some data',
                            'cf_23' => true,
                        ],
                    ],
                ],
                'total_count' => 2,
            ],
        ];
        yield 'not full data' => [
            'payload' => [
                'user_email' => 'test@example.com',
                'company_id' => [123],
            ],
            'response' => [
                [
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
                    ],
                ],
                'total_count' => 1,
            ],
        ];
        yield 'empty data' => [
            'payload' => [],
            'response' => [
                [
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
                    ],
                ],
                'total_count' => 1,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = FetchUserListPayload::from($payload);

        $url = $this->host.self::API_URL_USERS;
        $query = http_build_query($payload->toQuery(), '', '&', PHP_QUERY_RFC3986);

        if ($query != '') {
            $url .= '?'.$query;
        }

        Http::fake([
            $url => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->users()->fetchList($payload);

        Http::assertSent(function (Request $request) use ($url) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $url && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        /**
         * @var array<int, array{user: array<string, mixed>}> $usersRaw
         */
        $usersRaw = array_values($response);

        $users = collect($usersRaw)
            ->map(function (array $item) {
                return UserData::from($item['user']);
            });

        $this->assertEquals(new FetchUserListResponse(
            users: $users,
            total: $total
        ), $list);
    }
}
