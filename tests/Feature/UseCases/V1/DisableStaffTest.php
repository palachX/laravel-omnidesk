<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisableStaff\Payload as DisableStaffPayload;
use Palach\Omnidesk\UseCases\V1\DisableStaff\Response as DisableStaffResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class DisableStaffTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'disable staff' => [
            'staffId' => 200,
            'payload' => [
                'staff' => [
                    'replace_staff_id' => 300,
                ],
            ],
            'response' => [
                'staff' => [
                    'staff_id' => 200,
                    'staff_email' => 'staff@domain.ru',
                    'staff_full_name' => 'Staff full name changed',
                    'staff_signature' => 'Staff signature for email cases',
                    'staff_signature_chat' => 'Staff signature for chats',
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $staffId, array $payload, array $response): void
    {
        $payload = DisableStaffPayload::from($payload);

        $url = $this->host."/api/staff/$staffId/disable.json";

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->staffs()->disableStaff($staffId, $payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(DisableStaffResponse::from($response), $responseData);
    }
}
