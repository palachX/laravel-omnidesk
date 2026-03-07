<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\RecoveryCompany\Response as RecoveryCompanyResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class RecoveryCompanyTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'recovery company' => [
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
                    'deleted' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $companyId, array $response): void
    {
        $url = $this->host."/api/companies/$companyId/restore.json";

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->companies()->recoveryCompany($companyId);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode([]);
        });

        $this->assertEquals(RecoveryCompanyResponse::from($response), $responseData);
    }
}
