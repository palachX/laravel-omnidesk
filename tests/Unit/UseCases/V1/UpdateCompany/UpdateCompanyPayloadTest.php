<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateCompany;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateCompany\CompanyUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateCompany\Payload as UpdateCompanyPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateCompanyPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'company' => [
                    'company_name' => 'Test Company Updated',
                    'company_address' => '123 Test Street',
                    'company_note' => 'Test note',
                    'add_company_domains' => 'test.com,example.org',
                    'remove_company_domains' => 'old.com',
                    'company_default_group' => '123',
                    'add_company_users' => '100,101',
                    'remove_company_users' => '200,201',
                ],
            ],

            'expected' => new UpdateCompanyPayload(
                company: new CompanyUpdateData(
                    companyName: 'Test Company Updated',
                    addCompanyDomains: 'test.com,example.org',
                    removeCompanyDomains: 'old.com',
                    companyDefaultGroup: '123',
                    companyAddress: '123 Test Street',
                    companyNote: 'Test note',
                    addCompanyUsers: '100,101',
                    removeCompanyUsers: '200,201'
                )
            ),
        ];

        yield 'partial data' => [
            'data' => [
                'company' => [
                    'company_name' => 'Partial Update Company',
                    'company_note' => 'Updated note only',
                ],
            ],

            'expected' => new UpdateCompanyPayload(
                company: new CompanyUpdateData(
                    companyName: 'Partial Update Company',
                    companyNote: 'Updated note only',
                )
            ),
        ];

        yield 'domains only' => [
            'data' => [
                'company' => [
                    'add_company_domains' => 'newdomain.com,another.org',
                    'remove_company_domains' => 'olddomain.com',
                ],
            ],

            'expected' => new UpdateCompanyPayload(
                company: new CompanyUpdateData(
                    addCompanyDomains: 'newdomain.com,another.org',
                    removeCompanyDomains: 'olddomain.com',
                )
            ),
        ];

        yield 'users only' => [
            'data' => [
                'company' => [
                    'add_company_users' => '300,301,302',
                    'remove_company_users' => '100,101',
                ],
            ],

            'expected' => new UpdateCompanyPayload(
                company: new CompanyUpdateData(
                    addCompanyUsers: '300,301,302',
                    removeCompanyUsers: '100,101',
                )
            ),
        ];

        yield 'minimal data with one field' => [
            'data' => [
                'company' => [
                    'company_name' => 'Minimal Update',
                ],
            ],

            'expected' => new UpdateCompanyPayload(
                company: new CompanyUpdateData(
                    companyName: 'Minimal Update',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testValidateAndCreate(array $data, UpdateCompanyPayload $expected): void
    {
        $actual = UpdateCompanyPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }

    public function testToArray(): void
    {
        $payload = new UpdateCompanyPayload(
            company: new CompanyUpdateData(
                companyName: 'Test Company Updated',
                addCompanyDomains: 'test.com,example.org',
                removeCompanyDomains: 'old.com',
                companyDefaultGroup: '123',
                companyAddress: '123 Test Street',
                companyNote: 'Test note',
                addCompanyUsers: '100,101',
                removeCompanyUsers: '200,201'
            )
        );

        $expected = [
            'company' => [
                'company_name' => 'Test Company Updated',
                'company_address' => '123 Test Street',
                'company_note' => 'Test note',
                'add_company_domains' => 'test.com,example.org',
                'remove_company_domains' => 'old.com',
                'company_default_group' => '123',
                'add_company_users' => '100,101',
                'remove_company_users' => '200,201',
            ],
        ];

        $this->assertEquals($expected, $payload->toArray());
    }

    public function testWithEmptyOptionalFields(): void
    {
        $payload = new UpdateCompanyPayload(
            company: new CompanyUpdateData
        );

        $expected = [
            'company' => [],
        ];

        $this->assertEquals($expected, $payload->toArray());
    }
}
