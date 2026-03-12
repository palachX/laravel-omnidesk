<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\EnabledGroup;

use Palach\Omnidesk\DTO\GroupData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\EnabledGroup\Response as EnabledGroupResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class EnabledGroupResponseTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'enable group response' => [
            'data' => [
                'group' => [
                    'group_id' => 200,
                    'group_title' => 'Test group 2',
                    'group_from_name' => 'Test group 2 from name',
                    'group_signature' => 'Test group 2 signature',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
            'expected' => [
                'group' => [
                    'group_id' => 200,
                    'group_title' => 'Test group 2',
                    'group_from_name' => 'Test group 2 from name',
                    'group_signature' => 'Test group 2 signature',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testResponse(array $data, array $expected): void
    {
        $response = EnabledGroupResponse::from($data);

        $this->assertInstanceOf(GroupData::class, $response->group);
        $this->assertEquals($expected, $response->toArray());
    }
}
