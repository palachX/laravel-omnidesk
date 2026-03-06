<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreCompany\Payload as StoreCompanyPayload;
use Palach\Omnidesk\UseCases\V1\StoreCompany\Response as StoreCompanyResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class StoreCompanyTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'store company' => [
            'payload' => new StoreCompanyPayload(
                companyName: 'New Company',
                companyDomains: 'company.ru',
                companyDefaultGroup: 492,
                companyAddress: 'Some address',
                companyNote: 'Some note',
                companyUsers: '1351,1348,1347'
            ),
            'response' => [
                'company' => [
                    'company_id' => 200,
                    'company_name' => 'New Company',
                    'company_domains' => 'company.ru',
                    'company_default_group' => 492,
                    'company_address' => 'Some address',
                    'company_note' => 'Some note',
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(StoreCompanyPayload $payload, array $response): void
    {
        $url = $this->host.'/api/companies.json';

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->companies()->store($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_POST
                && $request->body() === json_encode(['company' => $payload->toArray()]);
        });

        $this->assertEquals(StoreCompanyResponse::from($response), $responseData);
    }
}
