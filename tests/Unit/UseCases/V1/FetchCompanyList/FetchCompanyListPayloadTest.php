<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchCompanyList;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCompanyList\Payload as FetchCompanyListPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchCompanyListPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'empty payload' => [
            'data' => [],
            'expected' => new FetchCompanyListPayload,
        ];

        yield 'payload with all parameters' => [
            'data' => [
                'page' => 1,
                'limit' => 50,
                'company_name' => 'Test Company',
                'company_domains' => 'example.com',
                'company_address' => 'Test Address',
                'company_note' => 'Test Note',
                'amount_of_users' => true,
                'amount_of_cases' => false,
            ],
            'expected' => new FetchCompanyListPayload(
                page: 1,
                limit: 50,
                companyName: 'Test Company',
                companyDomains: 'example.com',
                companyAddress: 'Test Address',
                companyNote: 'Test Note',
                amountOfUsers: true,
                amountOfCases: false,
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchCompanyListPayload $expected): void
    {
        $actual = FetchCompanyListPayload::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }
}
