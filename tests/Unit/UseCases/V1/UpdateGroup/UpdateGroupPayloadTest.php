<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateGroup;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateGroup\GroupUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateGroup\Payload as UpdateGroupPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateGroupPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'group' => [
                    'group_title' => 'Test Group Updated',
                    'group_from_name' => 'Test Group From Name',
                    'group_signature' => 'Test Group Signature',
                ],
            ],

            'expected' => new UpdateGroupPayload(
                group: new GroupUpdateData(
                    groupTitle: 'Test Group Updated',
                    groupFromName: 'Test Group From Name',
                    groupSignature: 'Test Group Signature'
                )
            ),
        ];

        yield 'partial data' => [
            'data' => [
                'group' => [
                    'group_title' => 'Partial Update Group',
                    'group_from_name' => 'Updated from name only',
                ],
            ],

            'expected' => new UpdateGroupPayload(
                group: new GroupUpdateData(
                    groupTitle: 'Partial Update Group',
                    groupFromName: 'Updated from name only',
                )
            ),
        ];

        yield 'title only' => [
            'data' => [
                'group' => [
                    'group_title' => 'Title Only Update',
                ],
            ],

            'expected' => new UpdateGroupPayload(
                group: new GroupUpdateData(
                    groupTitle: 'Title Only Update',
                )
            ),
        ];

        yield 'signature only' => [
            'data' => [
                'group' => [
                    'group_signature' => 'New signature only',
                ],
            ],

            'expected' => new UpdateGroupPayload(
                group: new GroupUpdateData(
                    groupSignature: 'New signature only',
                )
            ),
        ];

        yield 'from name only' => [
            'data' => [
                'group' => [
                    'group_from_name' => 'New from name only',
                ],
            ],

            'expected' => new UpdateGroupPayload(
                group: new GroupUpdateData(
                    groupFromName: 'New from name only',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testValidateAndCreate(array $data, UpdateGroupPayload $expected): void
    {
        $actual = UpdateGroupPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
