<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteCase\Payload as DeleteCasePayload;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class DeleteCaseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield [
            'payload' => ['case_id' => 2000],
        ];

        yield [
            'payload' => ['case_id' => 12345],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload): void
    {
        $payload = DeleteCasePayload::from($payload);
        $caseId = $payload->caseId;

        $url = $this->host."/api/cases/$caseId.json";

        Http::fake([
            $url => Http::response([], 200),
        ]);

        $this->makeHttpClient()->cases()->deleteCase($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_DELETE
                && $request->body() === json_encode([]);
        });
    }
}
