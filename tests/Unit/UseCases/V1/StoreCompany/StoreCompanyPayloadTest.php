<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreCompany;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreCompany\Payload as StoreCompanyPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreCompanyPayloadTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'company with all fields' => [
            'data' => [
                'company_name' => 'New Company',
                'company_domains' => 'company.ru',
                'company_default_group' => 492,
                'company_address' => 'Some address',
                'company_note' => 'Some note',
                'company_users' => '1351,1348,1347',
            ],
            'expected' => new StoreCompanyPayload(
                companyName: 'New Company',
                companyDomains: 'company.ru',
                companyDefaultGroup: 492,
                companyAddress: 'Some address',
                companyNote: 'Some note',
                companyUsers: '1351,1348,1347'
            ),
        ];

        yield 'company with only required field' => [
            'data' => [
                'company_name' => 'Minimal Company',
            ],
            'expected' => new StoreCompanyPayload(
                companyName: 'Minimal Company'
            ),
        ];

        yield 'company with some optional fields' => [
            'data' => [
                'company_name' => 'Partial Company',
                'company_domains' => 'partial.com, example.org',
                'company_note' => 'Some note',
            ],
            'expected' => new StoreCompanyPayload(
                companyName: 'Partial Company',
                companyDomains: 'partial.com, example.org',
                companyNote: 'Some note'
            ),
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testFromArray(array $data, StoreCompanyPayload $expected): void
    {
        $payload = StoreCompanyPayload::from($data);

        $this->assertEquals($expected, $payload);
    }

    #[DataProvider('dataArrayProvider')]
    public function testToArray(array $data, StoreCompanyPayload $expected): void
    {
        $payload = StoreCompanyPayload::from($data);

        $this->assertEquals($data, $payload->toArray());
    }
}
