<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreGroup;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreGroup\Payload as StoreGroupPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreGroupPayloadTest extends AbstractTestCase
{
    public static function payloadDataProvider(): iterable
    {
        yield 'full payload' => [
            'payload' => new StoreGroupPayload(
                groupTitle: 'Test group',
                groupFromName: 'Test group from name',
                groupSignature: 'Test group signature'
            ),
            'expected' => [
                'group_title' => 'Test group',
                'group_from_name' => 'Test group from name',
                'group_signature' => 'Test group signature',
            ],
        ];

        yield 'minimal payload' => [
            'payload' => new StoreGroupPayload(
                groupTitle: 'Test group'
            ),
            'expected' => [
                'group_title' => 'Test group',
            ],
        ];
    }

    #[DataProvider('payloadDataProvider')]
    public function testToArray(StoreGroupPayload $payload, array $expected): void
    {
        $this->assertEquals($expected, $payload->toArray());
    }
}
