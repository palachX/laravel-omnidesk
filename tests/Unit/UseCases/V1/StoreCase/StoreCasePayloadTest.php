<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreCase;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreCase\CaseStoreData;
use Palach\Omnidesk\UseCases\V1\StoreCase\Payload as StoreCasePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreCasePayloadTest extends AbstractTestCase
{
    public static function dataProviderSuccess(): iterable
    {
        yield 'full data email' => [
            'data' => [
                'case' => [
                    'user_email' => 'example@example.com',
                    'user_custom_id' => '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    'subject' => 'Subject case',
                    'content' => 'I need help',
                    'content_html' => '<p>I need help</p>',
                    'channel' => 'chh200',
                ],
            ],

            'expected' => new StoreCasePayload(
                case: new CaseStoreData(
                    userCustomId: '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    subject: 'Subject case',
                    content: 'I need help',
                    contentHtml: '<p>I need help</p>',
                    channel: 'chh200',
                    userEmail: 'example@example.com',
                )
            ),
        ];

        yield 'full data phone' => [
            'data' => [
                'case' => [
                    'user_phone' => '+79998887755',
                    'user_custom_id' => '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    'subject' => 'Subject case',
                    'content' => 'I need help',
                    'content_html' => '<p>I need help</p>',
                    'channel' => 'chh200',
                ],
            ],

            'expected' => new StoreCasePayload(
                case: new CaseStoreData(
                    userCustomId: '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    subject: 'Subject case',
                    content: 'I need help',
                    contentHtml: '<p>I need help</p>',
                    channel: 'chh200',
                    userPhone: '+79998887755',
                )
            ),
        ];

        yield 'full data email phone' => [
            'data' => [
                'case' => [
                    'user_email' => 'example@example.com',
                    'user_phone' => '+79998887755',
                    'user_custom_id' => '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    'subject' => 'Subject case',
                    'content' => 'I need help',
                    'content_html' => '<p>I need help</p>',
                    'channel' => 'chh200',
                ],
            ],

            'expected' => new StoreCasePayload(
                case: new CaseStoreData(
                    userCustomId: '8e334869-a6ca-41da-b5cd-a8a51f99a529',
                    subject: 'Subject case',
                    content: 'I need help',
                    contentHtml: '<p>I need help</p>',
                    channel: 'chh200',
                    userEmail: 'example@example.com',
                    userPhone: '+79998887755',
                )
            ),
        ];
    }

    #[DataProvider('dataProviderSuccess')]
    public function testFromArraySuccess(array $data, StoreCasePayload $expected): void
    {
        $actual = StoreCasePayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
