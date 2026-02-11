<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreCase;

use Palach\Omnidesk\DTO\CaseData;
use Palach\Omnidesk\DTO\FileData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreCase\Response as StoreCaseResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreCaseResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'case' => [
                    'case_id' => 2000,
                    'case_number' => '664-245651',
                    'subject' => 'Договор и счёт',
                    'user_id' => 123,
                    'staff_id' => 321,
                    'group_id' => 444,
                    'status' => 'waiting',
                    'priority' => 'normal',
                    'channel' => 'chh21',
                    'deleted' => false,
                    'spam' => false,
                    'attachments' => [
                        [
                            'file_id' => 345,
                            'file_name' => 'contract.pdf',
                            'file_size' => 40863,
                            'mime_type' => 'application/pdf',
                            'url' => 'https://[domain].omnidesk.ru/some_path_here/345',
                        ],
                        [
                            'file_id' => 346,
                            'file_name' => 'invoice.pdf',
                            'file_size' => 50863,
                            'mime_type' => 'application/pdf',
                            'url' => 'https://[domain].omnidesk.ru/some_path_here/346',
                        ],
                    ],
                ],
            ],

            'expected' => new StoreCaseResponse(
                case: new CaseData(
                    caseId: 2000,
                    caseNumber: '664-245651',
                    subject: 'Договор и счёт',
                    userId: 123,
                    staffId: 321,
                    groupId: 444,
                    status: 'waiting',
                    priority: 'normal',
                    channel: 'chh21',
                    deleted: false,
                    spam: false,
                    attachments: [
                        new FileData(
                            fileId: 345,
                            fileName: 'contract.pdf',
                            fileSize: 40863,
                            mimeType: 'application/pdf',
                            url: 'https://[domain].omnidesk.ru/some_path_here/345'
                        ),
                        new FileData(
                            fileId: 346,
                            fileName: 'invoice.pdf',
                            fileSize: 50863,
                            mimeType: 'application/pdf',
                            url: 'https://[domain].omnidesk.ru/some_path_here/346'
                        ),
                    ]
                )
            ),
        ];
        yield 'required data' => [
            'data' => [
                'case' => [
                    'case_id' => 2000,
                    'case_number' => '664-245651',
                    'subject' => 'Договор и счёт',
                    'user_id' => 123,
                    'staff_id' => 321,
                    'group_id' => 444,
                    'status' => 'waiting',
                    'priority' => 'normal',
                    'channel' => 'chh21',
                    'deleted' => false,
                    'spam' => false,
                ],
            ],

            'expected' => new StoreCaseResponse(
                case: new CaseData(
                    caseId: 2000,
                    caseNumber: '664-245651',
                    subject: 'Договор и счёт',
                    userId: 123,
                    staffId: 321,
                    groupId: 444,
                    status: 'waiting',
                    priority: 'normal',
                    channel: 'chh21',
                    deleted: false,
                    spam: false,
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, StoreCaseResponse $expected): void
    {
        $actual = StoreCaseResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
