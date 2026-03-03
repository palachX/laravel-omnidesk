<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateUser;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateUser\Payload;
use Palach\Omnidesk\UseCases\V1\UpdateUser\UserUpdateData;

final class UpdateUserPayloadTest extends AbstractTestCase
{
    public function testFromArray(): void
    {
        $data = [
            'user' => [
                'user_email' => 'test@example.com',
                'user_full_name' => 'Test User',
                'company_name' => 'Test Company',
                'language_id' => 1,
                'custom_fields' => [
                    'cf_1' => 'value1',
                    'cf_2' => true,
                ],
            ],
        ];

        $payload = Payload::from($data);

        $this->assertInstanceOf(UserUpdateData::class, $payload->user);
        $this->assertEquals('test@example.com', $payload->user->userEmail);
        $this->assertEquals('Test User', $payload->user->userFullName);
        $this->assertEquals('Test Company', $payload->user->companyName);
        $this->assertEquals(1, $payload->user->languageId);
        $this->assertEquals(['cf_1' => 'value1', 'cf_2' => true], $payload->user->customFields);
    }

    public function testToArray(): void
    {
        $payload = new Payload(
            user: new UserUpdateData(
                userEmail: 'test@example.com',
                userFullName: 'Test User',
                companyName: 'Test Company',
                languageId: 1,
                customFields: ['cf_1' => 'value1']
            )
        );

        $expected = [
            'user' => [
                'user_email' => 'test@example.com',
                'user_full_name' => 'Test User',
                'company_name' => 'Test Company',
                'language_id' => 1,
                'custom_fields' => ['cf_1' => 'value1'],
            ],
        ];

        $this->assertEquals($expected, $payload->toArray());
    }

    public function testWithEmptyOptionalFields(): void
    {
        $payload = new Payload(
            user: new UserUpdateData
        );

        $expected = [
            'user' => [],
        ];

        $this->assertEquals($expected, $payload->toArray());
    }
}
