<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\RestoreCase\BulkPayload as RestoreCaseBulkPayload;
use Palach\Omnidesk\UseCases\V1\RestoreCase\BulkResponse as RestoreCaseBulkResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class RestoreCaseBulkTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield [
            'payload' => ['case_ids' => [101, 102, 103, 104]],
            'response' => ['case_success_id' => [101, 102, 103, 104]],
        ];

        yield [
            'payload' => ['case_ids' => [2000, 3000]],
            'response' => ['case_success_id' => [2000, 3000]],
        ];

        yield [
            'payload' => ['case_ids' => [1]],
            'response' => ['case_success_id' => [1]],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = RestoreCaseBulkPayload::from($payload);
        $caseIds = implode(',', $payload->caseIds);

        $url = $this->host."/api/cases/$caseIds/restore.json";

        Http::fake([
            $url => Http::response($response, 200),
        ]);

        $actual = $this->makeHttpClient()->cases()->restoreBulk($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode([]);
        });

        $this->assertEquals(RestoreCaseBulkResponse::from($response), $actual);
    }
}
