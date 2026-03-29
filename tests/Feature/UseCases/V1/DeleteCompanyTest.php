<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteCompany\Payload as DeleteCompanyPayload;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class DeleteCompanyTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'delete company' => [
            'payload' => [
                'companyId' => 200,
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(array $payload): void
    {
        $payload = DeleteCompanyPayload::from($payload);
        $url = $this->host."/api/companies/$payload->companyId.json";

        Http::fake([
            $url => Http::response([]),
        ]);

        $this->makeHttpClient()->companies()->deleteCompany($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_DELETE
                && $request->body() === json_encode([]);
        });
    }
}
