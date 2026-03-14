<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateStaff\Payload as UpdateStaffPayload;
use Palach\Omnidesk\UseCases\V1\UpdateStaff\Response as UpdateStaffResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class UpdateStaffTest extends AbstractTestCase
{
    private const string API_URL_STAFF = '/api/staff.json';

    public static function dataArrayProvider(): iterable
    {
        yield [
            'staffId' => 200,
            'payload' => [
                'staff' => [
                    'staff_full_name' => 'Staff full name changed',
                ],
            ],
            'response' => [
                'staff' => [
                    'staff_id' => 200,
                    'staff_email' => 'staff@domain.ru',
                    'staff_full_name' => 'Staff full name changed',
                    'staff_signature' => 'Staff signature for email cases',
                    'staff_signature_chat' => 'Staff signature for chats',
                    'thumbnail' => '',
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
        yield [
            'staffId' => 201,
            'payload' => [
                'staff' => [
                    'staff_email' => 'updated.staff@example.com',
                    'staff_full_name' => 'John Doe Updated',
                    'staff_signature' => 'Best regards, John',
                    'staff_signature_chat' => 'John - Support Team',
                ],
            ],
            'response' => [
                'staff' => [
                    'staff_id' => 201,
                    'staff_email' => 'updated.staff@example.com',
                    'staff_full_name' => 'John Doe Updated',
                    'staff_signature' => 'Best regards, John',
                    'staff_signature_chat' => 'John - Support Team',
                    'thumbnail' => '',
                    'active' => true,
                    'created_at' => 'Wed, 15 Jun 2023 14:30:00 +0300',
                    'updated_at' => 'Thu, 25 Dec 2014 15:30:00 +0200',
                ],
            ],
        ];
        yield [
            'staffId' => 202,
            'payload' => [
                'staff' => [
                    'staff_signature' => 'Updated email signature',
                    'staff_signature_chat' => 'Updated chat signature',
                ],
            ],
            'response' => [
                'staff' => [
                    'staff_id' => 202,
                    'staff_email' => 'staff@example.com',
                    'staff_full_name' => 'Jane Smith',
                    'staff_signature' => 'Updated email signature',
                    'staff_signature_chat' => 'Updated chat signature',
                    'thumbnail' => '',
                    'active' => true,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Fri, 26 Dec 2014 11:20:00 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $staffId, array $payload, array $response): void
    {
        $url = $this->host.str_replace('.json', "/{$staffId}.json", self::API_URL_STAFF);

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->staffs()->update($staffId, UpdateStaffPayload::from($payload));

        $payload = UpdateStaffPayload::from($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(UpdateStaffResponse::from($response), $responseData);
    }
}
