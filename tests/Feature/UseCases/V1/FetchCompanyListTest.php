<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\CompanyData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCompanyList\Payload as FetchCompanyListPayload;
use Palach\Omnidesk\UseCases\V1\FetchCompanyList\Response as FetchCompanyListResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchCompanyListTest extends AbstractTestCase
{
    private const string API_URL_COMPANIES = '/api/companies.json';

    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'payload' => [
                'page' => 1,
                'limit' => 10,
                'company_name' => 'Test Company',
                'company_domains' => 'example.com',
                'company_address' => 'Test Address',
                'company_note' => 'Test Note',
                'amount_of_users' => true,
                'amount_of_cases' => false,
            ],
            'response' => [
                [
                    'company' => [
                        'company_id' => 200,
                        'company_name' => 'Test Company',
                        'company_domains' => 'example.com',
                        'company_default_group' => 492,
                        'company_address' => 'Test Address',
                        'company_note' => 'Test Note',
                        'active' => true,
                        'deleted' => false,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                        'amount_of_users' => 125,
                    ],
                ],
                [
                    'company' => [
                        'company_id' => 300,
                        'company_name' => 'Another Company',
                        'company_domains' => 'another.com',
                        'company_default_group' => 493,
                        'company_address' => 'Another Address',
                        'company_note' => 'Another Note',
                        'active' => true,
                        'deleted' => false,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                        'amount_of_users' => 75,
                    ],
                ],
                'total_count' => 2,
            ],
        ];
        yield 'not full data' => [
            'payload' => [
                'company_name' => 'Test Company',
                'amount_of_users' => true,
            ],
            'response' => [
                [
                    'company' => [
                        'company_id' => 200,
                        'company_name' => 'Test Company',
                        'company_domains' => 'example.com',
                        'company_default_group' => 492,
                        'company_address' => 'Test Address',
                        'company_note' => 'Test Note',
                        'active' => true,
                        'deleted' => false,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                        'amount_of_users' => 125,
                    ],
                ],
                'total_count' => 1,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = FetchCompanyListPayload::from($payload);

        $url = self::API_URL_COMPANIES;
        $query = http_build_query($payload->toQuery(), '', '&', PHP_QUERY_RFC3986);
        $fullUrl = $this->host.$url.'?'.$query;

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $list = $this->makeHttpClient()->companies()->fetchCompanyList(FetchCompanyListPayload::from($payload));

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $total = isset($response['total_count']) ? (int) $response['total_count'] : 0;

        unset($response['total_count']);

        /**
         * @var array<int, array{company: array<string, mixed>}> $companiesRaw
         */
        $companiesRaw = array_values($response);

        $companies = collect($companiesRaw)
            ->map(function (array $item) {
                return CompanyData::from($item['company']);
            });

        $this->assertEquals(new FetchCompanyListResponse(
            companies: $companies,
            total: $total
        ), $list);
    }
}
