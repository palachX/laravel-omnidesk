<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\TrashCase\BulkPayload as TrashCaseBulkPayload;
use Palach\Omnidesk\UseCases\V1\TrashCase\BulkResponse as TrashCaseBulkResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class TrashCaseBulkTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield [
            'payload' => ['case_ids' => [101, 102, 103, 104]],
            'response' => [
                'case_success_id' => [101, 102, 103, 104],
            ],
        ];

        yield [
            'payload' => ['case_ids' => [2000]],
            'response' => [
                'case_success_id' => [2000],
            ],
        ];

        yield [
            'payload' => ['case_ids' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]],
            'response' => [
                'case_success_id' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            ],
        ];

        yield [
            'payload' => ['case_ids' => [101, 102, 103, 104, 105]],
            'response' => [
                'case_success_id' => [101, 102, 104],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = TrashCaseBulkPayload::from($payload);
        $caseIds = $payload->caseIds;

        $url = $this->host.'/api/cases/'.implode(',', $caseIds).'/trash.json';

        Http::fake([
            $url => Http::response($response, 200),
        ]);

        $actual = $this->makeHttpClient()->cases()->trashBulk($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode([]);
        });

        $this->assertEquals(TrashCaseBulkResponse::from($response), $actual);
    }
}
