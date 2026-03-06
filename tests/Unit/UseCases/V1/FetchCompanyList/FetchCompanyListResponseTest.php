<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchCompanyList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\CompanyData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCompanyList\Response as FetchCompanyListResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchCompanyListResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'companies' => [
                    [
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
                        'amount_of_users' => 125,
                    ],
                    [
                        'company_id' => 300,
                        'company_name' => 'New Company 2',
                        'company_domains' => 'company.ru',
                        'company_default_group' => 492,
                        'company_address' => 'Some address',
                        'company_note' => 'Some note',
                        'active' => true,
                        'deleted' => false,
                        'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                        'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                        'amount_of_users' => 125,
                    ],
                ],
                'total' => 20,
            ],

            'expected' => new FetchCompanyListResponse(
                companies: new Collection([
                    new CompanyData(
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
                        amountOfUsers: 125,
                    ),
                    new CompanyData(
                        companyId: 300,
                        companyName: 'New Company 2',
                        companyDomains: 'company.ru',
                        companyDefaultGroup: 492,
                        companyAddress: 'Some address',
                        companyNote: 'Some note',
                        active: true,
                        deleted: false,
                        createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                        updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                        amountOfUsers: 125,
                    ),
                ]),
                total: 20
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchCompanyListResponse $expected): void
    {
        $actual = FetchCompanyListResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
