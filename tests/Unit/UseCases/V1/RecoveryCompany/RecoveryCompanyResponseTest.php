<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\RecoveryCompany;

use Palach\Omnidesk\DTO\CompanyData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\RecoveryCompany\Response as RecoveryCompanyResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class RecoveryCompanyResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'recovery company response' => [
            'data' => [
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
            'expected' => new RecoveryCompanyResponse(
                company: new CompanyData(
                    companyId: 200,
                    companyName: "Company's full name changed",
                    companyDomains: 'newcompany.ru',
                    companyDefaultGroup: 492,
                    companyAddress: 'Some address',
                    companyNote: 'New note',
                    active: true,
                    deleted: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, RecoveryCompanyResponse $expected): void
    {
        $actual = RecoveryCompanyResponse::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }
}
