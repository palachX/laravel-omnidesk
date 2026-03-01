<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteLabel\Payload;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class DeleteLabelTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield [
            'payload' => [
                'id' => 123,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload): void
    {
        $payload = Payload::from($payload);
        $id = $payload->id;

        $url = $this->host."/api/labels/$id.json";

        Http::fake([
            $url => Http::response([], 200),
        ]);

        $this->makeHttpClient()->labels()->deleteLabel($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->method() === SymfonyRequest::METHOD_DELETE;
        });
    }
}
