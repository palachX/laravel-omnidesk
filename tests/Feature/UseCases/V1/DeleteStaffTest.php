<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteStaff\Payload as DeleteStaffPayload;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class DeleteStaffTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'delete staff' => [
            'payload' => [
                'staffId' => 100,
                'staff' => [
                    'replace_staff_id' => 300,
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(array $payload): void
    {
        $payload = DeleteStaffPayload::from($payload);

        $url = $this->host."/api/staff/$payload->staffId.json";

        Http::fake([
            $url => Http::response([]),
        ]);

        $this->makeHttpClient()->staffs()->deleteStaff($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_DELETE
                && $request->body() === json_encode($payload->toArray());
        });
    }
}
