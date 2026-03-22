<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\EnableStaff\Payload as EnableStaffPayload;
use Palach\Omnidesk\UseCases\V1\EnableStaff\Response as EnabledStaffResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class EnableStaffTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'enable staff' => [
            'staffId' => 200,
            'response' => [
                'staff' => [
                    'staff_id' => 200,
                    'staff_email' => 'staff@domain.ru',
                    'staff_full_name' => 'Staff full name changed',
                    'staff_signature' => 'Staff signature for email cases',
                    'staff_signature_chat' => 'Staff signature for chats',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $staffId, array $response): void
    {
        $url = $this->host."/api/staff/$staffId/enable.json";

        Http::fake([
            $url => Http::response($response),
        ]);

        $payload = new EnableStaffPayload($staffId);
        $responseData = $this->makeHttpClient()->staffs()->enableStaff($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode([]);
        });

        $this->assertEquals(EnabledStaffResponse::from($response), $responseData);
    }
}
