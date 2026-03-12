<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteGroup\Payload as DeleteGroupPayload;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class DeleteGroupTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'delete group' => [
            'groupId' => 200,
            'payload' => new DeleteGroupPayload(
                replaceGroupId: 300
            ),
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(int $groupId, DeleteGroupPayload $payload): void
    {
        $url = $this->host."/api/groups/$groupId.json";

        Http::fake([
            $url => Http::response([]),
        ]);

        $this->makeHttpClient()->groups()->deleteGroup($groupId, $payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_DELETE
                && $request->body() === json_encode(['group' => $payload->toArray()]);
        });
    }
}
