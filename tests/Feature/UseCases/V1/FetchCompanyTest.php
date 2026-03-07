<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\CompanyData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCompany\Payload as FetchCompanyPayload;
use Palach\Omnidesk\UseCases\V1\FetchCompany\Response as FetchCompanyResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchCompanyTest extends AbstractTestCase
{
    private const string API_URL_COMPANY = '/api/companies/%d.json';

    public static function dataProvider(): iterable
    {
        yield 'full company data' => [
            'companyId' => 200,
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
        yield 'minimal company data' => [
            'companyId' => 201,
            'response' => [
                'company' => [
                    'company_id' => 201,
                    'company_name' => 'Simple Company',
                ],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(int $companyId, array $response): void
    {
        $payload = new FetchCompanyPayload($companyId);

        $url = sprintf(self::API_URL_COMPANY, $companyId);
        $fullUrl = $this->host.$url;

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $company = $this->makeHttpClient()->companies()->getCompany($payload);

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $this->assertEquals(new FetchCompanyResponse(
            company: CompanyData::from($response['company'])
        ), $company);
    }
}
