<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateCompany;

use Palach\Omnidesk\DTO\CompanyData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateCompany\Response as UpdateCompanyResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateCompanyResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'company' => [
                    'company_id' => 200,
                    'company_name' => 'Test Company Updated',
                    'company_domains' => 'test.com,example.org',
                    'company_default_group' => 123,
                    'company_address' => '123 Test Street',
                    'company_note' => 'Test note',
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],

            'expected' => new UpdateCompanyResponse(
                company: new CompanyData(
                    companyId: 200,
                    companyName: 'Test Company Updated',
                    companyDomains: 'test.com,example.org',
                    companyDefaultGroup: 123,
                    companyAddress: '123 Test Street',
                    companyNote: 'Test note',
                    active: true,
                    deleted: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                )
            ),
        ];

        yield 'minimal data' => [
            'data' => [
                'company' => [
                    'company_id' => 201,
                    'company_name' => 'Minimal Company',
                    'company_domains' => '',
                    'company_default_group' => 0,
                    'company_address' => '',
                    'company_note' => '',
                    'active' => true,
                    'deleted' => false,
                    'created_at' => 'Wed, 15 Jun 2023 14:30:00 +0300',
                    'updated_at' => 'Thu, 25 Dec 2014 15:30:00 +0200',
                ],
            ],

            'expected' => new UpdateCompanyResponse(
                company: new CompanyData(
                    companyId: 201,
                    companyName: 'Minimal Company',
                    companyDomains: '',
                    companyDefaultGroup: 0,
                    companyAddress: '',
                    companyNote: '',
                    active: true,
                    deleted: false,
                    createdAt: 'Wed, 15 Jun 2023 14:30:00 +0300',
                    updatedAt: 'Thu, 25 Dec 2014 15:30:00 +0200',
                )
            ),
        ];

        yield 'inactive company' => [
            'data' => [
                'company' => [
                    'company_id' => 202,
                    'company_name' => 'Inactive Company',
                    'company_domains' => 'inactive.com',
                    'company_default_group' => 456,
                    'company_address' => '',
                    'company_note' => 'Company is inactive',
                    'active' => false,
                    'deleted' => false,
                    'created_at' => 'Thu, 20 Jul 2023 09:15:00 +0300',
                    'updated_at' => 'Fri, 26 Dec 2014 11:20:00 +0200',
                ],
            ],

            'expected' => new UpdateCompanyResponse(
                company: new CompanyData(
                    companyId: 202,
                    companyName: 'Inactive Company',
                    companyDomains: 'inactive.com',
                    companyDefaultGroup: 456,
                    companyAddress: '',
                    companyNote: 'Company is inactive',
                    active: false,
                    deleted: false,
                    createdAt: 'Thu, 20 Jul 2023 09:15:00 +0300',
                    updatedAt: 'Fri, 26 Dec 2014 11:20:00 +0200',
                )
            ),
        ];

        yield 'deleted company' => [
            'data' => [
                'company' => [
                    'company_id' => 203,
                    'company_name' => 'Deleted Company',
                    'company_domains' => 'deleted.com',
                    'company_default_group' => 789,
                    'company_address' => 'Some address',
                    'company_note' => 'Company was deleted',
                    'active' => false,
                    'deleted' => true,
                    'created_at' => 'Fri, 25 Aug 2023 16:45:00 +0300',
                    'updated_at' => 'Sat, 27 Dec 2014 08:10:00 +0200',
                ],
            ],

            'expected' => new UpdateCompanyResponse(
                company: new CompanyData(
                    companyId: 203,
                    companyName: 'Deleted Company',
                    companyDomains: 'deleted.com',
                    companyDefaultGroup: 789,
                    companyAddress: 'Some address',
                    companyNote: 'Company was deleted',
                    active: false,
                    deleted: true,
                    createdAt: 'Fri, 25 Aug 2023 16:45:00 +0300',
                    updatedAt: 'Sat, 27 Dec 2014 08:10:00 +0200',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, UpdateCompanyResponse $expected): void
    {
        $actual = UpdateCompanyResponse::from($data);

        $this->assertEquals($expected, $actual);
    }

    public function testToArray(): void
    {
        $companyData = new CompanyData(
            companyId: 200,
            companyName: 'Test Company Updated',
            companyDomains: 'test.com,example.org',
            companyDefaultGroup: 123,
            companyAddress: '123 Test Street',
            companyNote: 'Test note',
            active: true,
            deleted: false,
            createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
            updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
        );

        $response = new UpdateCompanyResponse(company: $companyData);

        $expected = [
            'company' => [
                'company_id' => 200,
                'company_name' => 'Test Company Updated',
                'company_domains' => 'test.com,example.org',
                'company_default_group' => 123,
                'company_address' => '123 Test Street',
                'company_note' => 'Test note',
                'active' => true,
                'deleted' => false,
                'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
            ],
        ];

        $this->assertEquals($expected, $response->toArray());
    }
}
