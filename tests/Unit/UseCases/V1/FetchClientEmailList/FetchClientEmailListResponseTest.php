<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchClientEmailList;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\ClientEmailData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchClientEmailList\Response as FetchClientEmailListResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchClientEmailListResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data with multiple client emails' => [
            'data' => [
                'client_emails' => [
                    [
                        'email_id' => 7,
                        'email' => 'example@gmail.com',
                        'active' => true,
                    ],
                    [
                        'email_id' => 9,
                        'email' => 'support@example.omnidesk.ru',
                        'active' => true,
                    ],
                    [
                        'email_id' => 127,
                        'email' => 'test@example.omnidesk.ru',
                        'active' => false,
                    ],
                ],
                'total_count' => 3,
            ],

            'expected' => new FetchClientEmailListResponse(
                clientEmails: new Collection([
                    new ClientEmailData(
                        emailId: 7,
                        email: 'example@gmail.com',
                        active: true,
                    ),
                    new ClientEmailData(
                        emailId: 9,
                        email: 'support@example.omnidesk.ru',
                        active: true,
                    ),
                    new ClientEmailData(
                        emailId: 127,
                        email: 'test@example.omnidesk.ru',
                        active: false,
                    ),
                ]),
                totalCount: 3
            ),
        ];

        yield 'empty client emails list' => [
            'data' => [
                'client_emails' => [],
                'total_count' => 0,
            ],

            'expected' => new FetchClientEmailListResponse(
                clientEmails: new Collection([]),
                totalCount: 0
            ),
        ];

        yield 'single client email' => [
            'data' => [
                'client_emails' => [
                    [
                        'email_id' => 1,
                        'email' => 'single@example.com',
                        'active' => true,
                    ],
                ],
                'total_count' => 1,
            ],

            'expected' => new FetchClientEmailListResponse(
                clientEmails: new Collection([
                    new ClientEmailData(
                        emailId: 1,
                        email: 'single@example.com',
                        active: true,
                    ),
                ]),
                totalCount: 1
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchClientEmailListResponse $expected): void
    {
        $actual = FetchClientEmailListResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
