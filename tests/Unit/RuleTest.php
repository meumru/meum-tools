<?php

namespace MeumTools\Tests\Unit;

use Exception;
use Meumru\MeumTools\Exceptions\RuleCheckException;
use Meumru\MeumTools\Exceptions\RuleException;
use Meumru\MeumTools\Interfaces\RuleInterface;
use Meumru\MeumTools\Rule;
use PHPUnit\Framework\TestCase;

/**
 * @see Rule
 */
class RuleTest extends TestCase
{
    /**
     * @see Rule::checkValue();
     * @dataProvider checkValueCases
     *
     * @param Rule $rule
     * @param $value
     * @param string|null $exception
     */
    public function testCheckValue(Rule $rule, $value, ?string $exception = null): void
    {
        if ($exception !== null) {
            self::expectException($exception);
        }
        $rule->checkValue($value);

        self::addToAssertionCount(1);
    }

    public function checkValueCases(): array
    {
        return [
            'required for null' => [Rule::create()->required(), null, RuleCheckException::class],
            'required for integer' => [Rule::create()->required(), 1],
            'required for empty string' => [Rule::create()->required(), '', RuleCheckException::class],
            'required for string' => [Rule::create()->required(), 'test'],
            'required for boolean' => [Rule::create()->required(), false],
            'integer for integer' => [Rule::create()->typeInt(), 1],
            'integer for string' => [Rule::create()->typeInt(), '1', RuleCheckException::class],
            'string for integer' => [Rule::create()->typeString(), 1, RuleCheckException::class],
            'string for string' => [Rule::create()->typeString(), '1'],
            'bool for bool' => [Rule::create()->typeBool(), false],
            'correct instance' => [Rule::create()->instanceOf(Rule::class), new Rule()],
            'correct instance interface' => [Rule::create()->instanceOf(RuleInterface::class), new Rule()],
            'correct instance extending' => [Rule::create()->instanceOf(Exception::class), new RuleCheckException()],
            'incorrect extending order' => [Rule::create()->instanceOf(RuleCheckException::class), new Exception(), RuleCheckException::class],
            'incorrect instance' => [Rule::create()->instanceOf(Rule::class), new RuleCheckException(), RuleCheckException::class],
        ];
    }

    /**
     * @see Rule::instanceOf()
     */
    public function testInstanceOfForInteger(): void
    {
        $rule = Rule::create()->typeInt();

        self::expectException(RuleException::class);

        $rule->instanceOf(Rule::class);
    }

    /**
     * @see Rule::typeInt()
     */
    public function testTypeIntForInstance(): void
    {
        $rule = Rule::create()->instanceOf(Rule::class);

        self::expectException(RuleException::class);

        $rule->typeInt();
    }
}
