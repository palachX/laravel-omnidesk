<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreStaff\Payload as StoreStaffPayload;
use Palach\Omnidesk\UseCases\V1\StoreStaff\Response as StoreStaffResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class StoreStaffTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'store staff' => [
            'payload' => new StoreStaffPayload(
                staffEmail: 'staff@domain.ru',
                staffFullName: 'Staff full name',
                staffSignature: 'Staff signature for email cases',
                staffSignatureChat: 'Staff signature for chats'
            ),
            'response' => [
                'staff' => [
                    'staff_id' => 200,
                    'staff_email' => 'staff@domain.ru',
                    'staff_full_name' => 'Staff full name',
                    'staff_signature' => 'Staff signature for email cases',
                    'staff_signature_chat' => 'Staff signature for chats',
                    'thumbnail' => '',
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(StoreStaffPayload $payload, array $response): void
    {
        $url = $this->host.'/api/staff.json';

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->staffs()->store($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_POST
                && $request->body() === json_encode(['staff' => $payload->toArray()]);
        });

        $this->assertEquals(StoreStaffResponse::from($response), $responseData);
    }
}
