<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteStaff\Payload as DeleteStaffPayload;
use Palach\Omnidesk\UseCases\V1\DeleteStaff\Response as DeleteStaffResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class DeleteStaffTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'delete staff' => [
            'staffId' => 100,
            'payload' => [
                'staff' => [
                    'replace_staff_id' => 300,
                ],
            ],
            'response' => [
                'staff' => [
                    'staff_id' => 100,
                    'staff_full_name' => 'John Doe',
                    'staff_email' => 'john@example.com',
                    'staff_signature' => '',
                    'staff_signature_chat' => '',
                    'thumbnail' => '',
                    'active' => false,
                    'status' => 'deleted',
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $staffId, array $payload, array $response): void
    {
        $payload = DeleteStaffPayload::from($payload);

        $url = $this->host."/api/staff/$staffId.json";

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->staffs()->deleteStaff($staffId, $payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_DELETE
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(DeleteStaffResponse::from($response), $responseData);
    }
}
