<?php

namespace MeumTools\Tests\Unit\Helpers;

use Meumru\MeumTools\Helpers\Str;
use PHPUnit\Framework\TestCase;

/**
 * @see Str
 */
class StrTest extends TestCase
{
    /**
     * @see Str::format()
     * @dataProvider formatCases
     *
     * @param string $format
     * @param array $arguments
     * @param string $expected
     */
    public function testFormat(string $format, array $arguments, string $expected): void
    {
        $result = Str::format($format, $arguments);

        self::assertEquals($expected, $result);
    }

    public function formatCases(): array
    {
        return [
            'without arguments' => ['Test format', [], 'Test format'],
            'with 1 argument' => ['Test {$count} arg', ['count' => 1], 'Test 1 arg'],
            'with 2 arguments' => ['Test {$count} {$countable}', ['count' => 2, 'countable' => 'args'], 'Test 2 args'],
            'with unused marker' => ['Test {$count} {$countable}', ['count' => 2], 'Test 2 {$countable}'],
            'with rest argument' => ['Test {$count}', ['count' => 2, 'countable' => 'args'], 'Test 2'],
        ];
    }
}