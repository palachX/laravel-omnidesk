<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateCompany\Payload as UpdateCompanyPayload;
use Palach\Omnidesk\UseCases\V1\UpdateCompany\Response as UpdateCompanyResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class UpdateCompanyTest extends AbstractTestCase
{
    private const string API_URL_COMPANIES = '/api/companies.json';

    public static function dataArrayProvider(): iterable
    {
        yield [
            'companyId' => 200,
            'payload' => [
                'company' => [
                    'company_name' => "Company's full name changed",
                    'company_note' => 'New note',
                    'add_company_domains' => 'newcompany.ru',
                    'remove_company_domains' => 'company.ru',
                ],
            ],
            'response' => [
                'company' => [
                    'company_id' => 200,
                    'company_name' => "Company's full name changed",
                    'company_domains' => 'newcompany.ru',
                    'company_default_group' => 492,
                    'company_address' => 'Some address',
                    'company_note' => 'New note',
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
        yield [
            'companyId' => 201,
            'payload' => [
                'company' => [
                    'company_name' => 'Tech Company Updated',
                    'company_address' => '123 New Street, City',
                    'company_default_group' => '123',
                    'add_company_users' => '100,101,102',
                    'remove_company_users' => '200,201',
                ],
            ],
            'response' => [
                'company' => [
                    'company_id' => 201,
                    'company_name' => 'Tech Company Updated',
                    'company_domains' => 'techcompany.com',
                    'company_default_group' => 123,
                    'company_address' => '123 New Street, City',
                    'company_note' => '',
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Wed, 15 Jun 2023 14:30:00 +0300',
                    'updated_at' => 'Thu, 25 Dec 2014 15:30:00 +0200',
                ],
            ],
        ];
        yield [
            'companyId' => 202,
            'payload' => [
                'company' => [
                    'company_name' => 'Global Corp Inc',
                    'company_note' => 'VIP client updated',
                    'add_company_domains' => 'global.com,global.org',
                    'remove_company_domains' => 'oldcorp.com',
                ],
            ],
            'response' => [
                'company' => [
                    'company_id' => 202,
                    'company_name' => 'Global Corp Inc',
                    'company_domains' => 'global.com,global.org',
                    'company_default_group' => 456,
                    'company_address' => '',
                    'company_note' => 'VIP client updated',
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Fri, 26 Dec 2014 11:20:00 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $companyId, array $payload, array $response): void
    {
        $url = $this->host.str_replace('.json', "/{$companyId}.json", self::API_URL_COMPANIES);

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->companies()->update($companyId, UpdateCompanyPayload::from($payload));

        $payload = UpdateCompanyPayload::from($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(UpdateCompanyResponse::from($response), $responseData);
    }
}
