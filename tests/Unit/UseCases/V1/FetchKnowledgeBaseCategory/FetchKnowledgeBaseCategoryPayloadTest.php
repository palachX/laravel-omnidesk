<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchKnowledgeBaseCategory;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategory\Payload as FetchKnowledgeBaseCategoryPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchKnowledgeBaseCategoryPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'valid category id' => [
            'data' => [
                'category_id' => 234,
            ],
            'expected' => new FetchKnowledgeBaseCategoryPayload(
                categoryId: 234,
            ),
        ];

        yield 'category id with language id' => [
            'data' => [
                'category_id' => 235,
                'language_id' => 'en',
            ],
            'expected' => new FetchKnowledgeBaseCategoryPayload(
                categoryId: 235,
                languageId: 'en',
            ),
        ];

        yield 'category id with all languages' => [
            'data' => [
                'category_id' => 236,
                'language_id' => 'all',
            ],
            'expected' => new FetchKnowledgeBaseCategoryPayload(
                categoryId: 236,
                languageId: 'all',
            ),
        ];

        yield 'minimum category id' => [
            'data' => [
                'category_id' => 1,
            ],
            'expected' => new FetchKnowledgeBaseCategoryPayload(
                categoryId: 1,
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchKnowledgeBaseCategoryPayload $expected): void
    {
        $actual = FetchKnowledgeBaseCategoryPayload::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }

    public function testConstructor(): void
    {
        $payload = new FetchKnowledgeBaseCategoryPayload(234);

        $this->assertSame(234, $payload->categoryId);
        $this->assertIsInt($payload->categoryId);
    }

    public function testConstructorWithLanguageId(): void
    {
        $payload = new FetchKnowledgeBaseCategoryPayload(234, 'en');

        $this->assertSame(234, $payload->categoryId);
        $this->assertSame('en', $payload->languageId);
        $this->assertIsInt($payload->categoryId);
        $this->assertIsString($payload->languageId);
    }

    public function testCategoryIdIsReadOnly(): void
    {
        $payload = new FetchKnowledgeBaseCategoryPayload(234);

        // Verify the property is readonly by trying to access it as a property
        $this->assertSame(234, $payload->categoryId);
    }

    public function testLanguageIdIsReadOnly(): void
    {
        $payload = new FetchKnowledgeBaseCategoryPayload(234, 'en');

        // Verify the property is readonly by trying to access it as a property
        $this->assertSame('en', $payload->languageId);
    }
}
