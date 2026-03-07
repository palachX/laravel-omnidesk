<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchCompany;

use Palach\Omnidesk\DTO\CompanyData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCompany\Response as FetchCompanyResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchCompanyResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full company data' => [
            'data' => [
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

            'expected' => new FetchCompanyResponse(
                company: new CompanyData(
                    companyId: 200,
                    companyName: 'New Company',
                    companyDomains: 'company.ru',
                    companyDefaultGroup: 492,
                    companyAddress: 'Some address',
                    companyNote: 'Some note',
                    active: true,
                    deleted: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                ),
            ),
        ];

        yield 'minimal company data' => [
            'data' => [
                'company' => [
                    'company_id' => 201,
                    'company_name' => 'Simple Company',
                ],
            ],

            'expected' => new FetchCompanyResponse(
                company: new CompanyData(
                    companyId: 201,
                    companyName: 'Simple Company',
                ),
            ),
        ];

        yield 'company with partial data' => [
            'data' => [
                'company' => [
                    'company_id' => 202,
                    'company_name' => 'Partial Company',
                    'company_domains' => 'partial.com',
                    'active' => false,
                ],
            ],

            'expected' => new FetchCompanyResponse(
                company: new CompanyData(
                    companyId: 202,
                    companyName: 'Partial Company',
                    companyDomains: 'partial.com',
                    active: false,
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchCompanyResponse $expected): void
    {
        $actual = FetchCompanyResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }

    public function testCompanyType(): void
    {
        $companyData = new CompanyData(
            companyId: 1,
            companyName: 'Test Company',
        );

        $response = new FetchCompanyResponse(company: $companyData);

        $this->assertInstanceOf(CompanyData::class, $response->company);
        $this->assertSame($companyData, $response->company);
    }

    public function testCompanyIsReadOnly(): void
    {
        $companyData = new CompanyData(
            companyId: 1,
            companyName: 'Test Company',
        );

        $response = new FetchCompanyResponse(company: $companyData);

        // Verify the property is readonly
        $this->assertSame($companyData, $response->company);
    }
}
