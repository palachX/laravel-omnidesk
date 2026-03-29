<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteCase\BulkPayload as DeleteCaseBulkPayload;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class DeleteCaseBulkTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield [
            'payload' => ['case_ids' => [101, 102, 103, 104]],
        ];

        yield [
            'payload' => ['case_ids' => [2000, 3000]],
        ];

        yield [
            'payload' => ['case_ids' => [1]],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload): void
    {
        $payload = DeleteCaseBulkPayload::from($payload);
        $caseIds = implode(',', $payload->caseIds);

        $url = $this->host."/api/cases/$caseIds.json";

        Http::fake([
            $url => Http::response([], 200),
        ]);

        $this->makeHttpClient()->cases()->deleteBulk($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_DELETE
                && $request->body() === json_encode([]);
        });
    }
}
