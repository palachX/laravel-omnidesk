<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreCompany;

use Palach\Omnidesk\DTO\CompanyData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreCompany\Response as StoreCompanyResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreCompanyResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'store company response' => [
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
            'expected' => new StoreCompanyResponse(
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
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, StoreCompanyResponse $expected): void
    {
        $actual = StoreCompanyResponse::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }
}
