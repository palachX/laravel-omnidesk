<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisableCompany\Payload as DisableCompanyPayload;
use Palach\Omnidesk\UseCases\V1\DisableCompany\Response as DisabledCompanyResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class DisableCompanyTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'disable company' => [
            'companyId' => 200,
            'response' => [
                'company' => [
                    'company_id' => 200,
                    'company_name' => "Company's full name changed",
                    'company_domains' => 'newcompany.ru',
                    'company_default_group' => 492,
                    'company_address' => 'Some address',
                    'company_note' => 'New note',
                    'active' => true,
                    'deleted' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $companyId, array $response): void
    {
        $url = $this->host."/api/companies/$companyId/disable.json";

        Http::fake([
            $url => Http::response($response),
        ]);

        $payload = new DisableCompanyPayload($companyId);
        $responseData = $this->makeHttpClient()->companies()->disableCompany($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode([]);
        });

        $this->assertEquals(DisabledCompanyResponse::from($response), $responseData);
    }
}
