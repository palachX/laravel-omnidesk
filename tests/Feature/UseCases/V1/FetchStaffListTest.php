<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\StaffData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchStaffList\Payload as FetchStaffListPayload;
use Palach\Omnidesk\UseCases\V1\FetchStaffList\Response as FetchStaffListResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchStaffListTest extends AbstractTestCase
{
    private const string API_URL_STAFF = '/api/staff.json';

    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'payload' => [
                'page' => 1,
                'limit' => 10,
                'language_id' => 'en',
            ],
            'response' => [
                [
                    'staff' => [
                        'staff_id' => 200,
                        'staff_email' => 'staff@domain.ru',
                        'staff_signature' => 'Staff signature for email cases',
                        'staff_signature_chat' => 'Staff signature for chats',
                        'thumbnail' => '',
                        'active' => false,
                        'status' => 'online',
                        'status_id' => 1,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ],
                ],
                [
                    'staff' => [
                        'staff_id' => 210,
                        'staff_email' => 'staff2@domain.ru',
                        'staff_signature' => 'Staff 2 signature for email cases',
                        'staff_signature_chat' => 'Staff 2 signature for chats',
                        'thumbnail' => 'http://domain.omnidesk.ru/path/avatar.jpeg',
                        'active' => true,
                        'status' => 'offline',
                        'status_id' => 2,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total_count' => 10,
            ],
        ];
        yield 'not full data' => [
            'payload' => [
                'language_id' => 'ru',
            ],
            'response' => [
                [
                    'staff' => [
                        'staff_id' => 200,
                        'staff_email' => 'staff@domain.ru',
                        'staff_signature' => 'Staff signature for email cases',
                        'staff_signature_chat' => 'Staff signature for chats',
                        'thumbnail' => '',
                        'active' => false,
                        'status' => 'online',
                        'status_id' => 1,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    ],
                ],
                'total_count' => 1,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = FetchStaffListPayload::from($payload);

        $url = self::API_URL_STAFF;
        $query = http_build_query($payload->toQuery(), '', '&', PHP_QUERY_RFC3986);
        $fullUrl = $this->host.$url.'?'.$query;

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->staffs()->fetchStaffList(FetchStaffListPayload::from($payload));

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        /**
         * @var array<int, array{staff: array<string, mixed>}> $staffsRaw
         */
        $staffsRaw = array_values($response);

        $staffs = collect($staffsRaw)
            ->map(function (array $item) {
                return StaffData::from($item['staff']);
            });

        $this->assertEquals(new FetchStaffListResponse(
            staffs: $staffs,
            total: $total
        ), $list);
    }
}
