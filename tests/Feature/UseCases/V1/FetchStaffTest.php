<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\StaffData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchStaff\Payload as FetchStaffPayload;
use Palach\Omnidesk\UseCases\V1\FetchStaff\Response as FetchStaffResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchStaffTest extends AbstractTestCase
{
    private const string API_URL_STAFF = '/api/staff/%d.json';

    public static function dataProvider(): iterable
    {
        yield 'full staff data' => [
            'payload' => [
                'staffId' => 200,
            ],
            'response' => [
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
        ];
        yield 'minimal staff data' => [
            'payload' => [
                'staffId' => 200,
            ],
            'response' => [
                'staff' => [
                    'staff_id' => 201,
                    'staff_email' => 'minimal@domain.ru',
                ],
            ],
        ];
        yield 'staff data with language' => [
            'payload' => [
                'staffId' => 200,
                'language_id' => 10,
            ],
            'response' => [
                'staff' => [
                    'staff_id' => 202,
                    'staff_email' => 'john@domain.ru',
                    'staff_full_name' => 'John Doe',
                    'active' => true,
                    'status_id' => 2,
                ],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = FetchStaffPayload::from($payload);

        $url = sprintf(self::API_URL_STAFF, $payload->staffId);
        $query = http_build_query($payload->toQuery(), '', '&', PHP_QUERY_RFC3986);
        $fullUrl = $this->host.$url;

        if ($query !== '') {
            $fullUrl .= '?'.$query;
        }

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $staff = $this->makeHttpClient()->staffs()->fetchStaff($payload);

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $this->assertEquals(new FetchStaffResponse(
            staff: StaffData::from($response['staff'])
        ), $staff);
    }
}
