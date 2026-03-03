<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateUser;

use Palach\Omnidesk\DTO\UserData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateUser\Response;

final class UpdateUserResponseTest extends AbstractTestCase
{
    public function testFromArray(): void
    {
        $data = [
            'user' => [
                'user_id' => 200,
                'user_full_name' => 'Test User',
                'company_name' => 'Test Company',
                'company_position' => 'Developer',
                'thumbnail' => '',
                'confirmed' => false,
                'active' => true,
                'deleted' => false,
                'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                'type' => 'email',
                'user_email' => 'test@example.com',
                'language_id' => 1,
                'custom_fields' => [
                    'cf_1' => 'value1',
                    'cf_2' => true,
                ],
            ],
        ];

        $response = Response::from($data);

        $this->assertInstanceOf(UserData::class, $response->user);
        $this->assertEquals(200, $response->user->userId);
        $this->assertEquals('Test User', $response->user->userFullName);
        $this->assertEquals('Test Company', $response->user->companyName);
        $this->assertEquals('Developer', $response->user->companyPosition);
        $this->assertEquals('test@example.com', $response->user->userEmail);
        $this->assertEquals(1, $response->user->languageId);
        $this->assertEquals(['cf_1' => 'value1', 'cf_2' => true], $response->user->customFields);
    }

    public function testToArray(): void
    {
        $userData = new UserData(
            userId: 200,
            userFullName: 'Test User',
            companyName: 'Test Company',
            userEmail: 'test@example.com',
            languageId: 1
        );

        $response = new Response(user: $userData);

        $expected = [
            'user' => [
                'user_id' => 200,
                'user_full_name' => 'Test User',
                'company_name' => 'Test Company',
                'user_email' => 'test@example.com',
                'language_id' => 1,
            ],
        ];

        $this->assertEquals($expected, $response->toArray());
    }
}
