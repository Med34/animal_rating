<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Services\NameNormalizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NameNormalizerTest extends TestCase
{
    private NameNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->normalizer = new NameNormalizer();
    }

    #[DataProvider('provideNames')]
    public function testNormalizeLowercasesAndTrims(string $input, string $expected): void
    {
        self::assertSame($expected, $this->normalizer->normalize($input));
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function provideNames(): iterable
    {
        yield 'already clean' => ['chat', 'chat'];
        yield 'uppercase' => ['CHAT', 'chat'];
        yield 'surrounding spaces' => ['  Jean  ', 'jean'];
        yield 'mixed case + accents' => ['PanThèRe', 'panthère'];
        yield 'with hyphen' => ['Jean-Pierre', 'jean-pierre'];
        yield 'name with space instead of hyphen' => ['Jean Pierre', 'jean pierre'];
    }
}
