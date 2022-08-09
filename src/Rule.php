<?php

namespace Meumru\MeumTools;

use Meumru\MeumTools\Exceptions\RuleCheckException;
use Meumru\MeumTools\Exceptions\RuleException;
use Meumru\MeumTools\Interfaces\RuleInterface;

class Rule implements RuleInterface
{
    public const TYPE_INT = 'integer';
    public const TYPE_STRING = 'string';
    public const TYPE_BOOL = 'boolean';
    public const TYPE_OBJECT = 'object';
    public const TYPE_DOUBLE = 'double';

    protected static array $types = [
        self::TYPE_INT,
        self::TYPE_STRING,
        self::TYPE_BOOL,
        self::TYPE_OBJECT,
    ];

    protected bool $required = false;
    protected ?string $instanceOf = null;
    protected ?string $type = null;


    public static function create(): self
    {
        return new static();
    }

    public function required(bool $required = true): self
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @param string $className
     * @return $this
     * @throws RuleException
     */
    public function instanceOf(string $className): self
    {
        if ($this->type !== null && $this->type !== self::TYPE_OBJECT) {
            throw RuleException::nonObjectTypeWithInstanceOf($this->type, $className);
        }
        $this->type = self::TYPE_OBJECT;
        $this->instanceOf = $className;

        return $this;
    }

    /**
     * @return $this
     * @throws RuleException
     */
    public function typeInt(): self
    {
        return $this->type(self::TYPE_INT);
    }

    /**
     * @return $this
     * @throws RuleException
     */
    public function typeString(): self
    {
        return $this->type(self::TYPE_STRING);
    }

    /**
     * @return $this
     * @throws RuleException
     */
    public function typeBool(): self
    {
        return $this->type(self::TYPE_BOOL);
    }

    /**
     * @return $this
     * @throws RuleException
     */
    public function typeObject(): self
    {
        return $this->type(self::TYPE_OBJECT);
    }

    /**
     * @return $this
     * @throws RuleException
     */
    public function typeDouble(): self
    {
        return $this->type(self::TYPE_DOUBLE);
    }

    public function getRequired(): bool
    {
        return $this->required;
    }

    public function getInstanceOf(): bool
    {
        return $this->instanceOf;
    }

    public function getIsObject(): bool
    {
        return $this->type === self::TYPE_OBJECT;
    }

    public function checkValue($value): void
    {
        $this->checkValueType($value);

        if ($this->valueIsEmpty($value)) {
            $this->checkEmptyValue($value);

            return;
        }

        if (!$this->getIsObject()) {
            return;
        }

        $this->checkInstanceOf($value);
    }

    /**
     * @param string $type
     * @return $this
     * @throws RuleException
     */
    protected function type(string $type): self
    {
        if (!in_array($type, static::$types)) {
            throw RuleException::wrongType($type);
        }
        if ($type !== self::TYPE_OBJECT && $this->instanceOf !== null) {
            throw RuleException::nonObjectTypeWithInstanceOf($type, $this->instanceOf);
        }

        $this->type = $type;

        return $this;
    }

    protected function valueIsEmpty($value): bool
    {
        return in_array($value, ['', null, []], true);
    }
    protected function checkEmptyValue(?string $value): void
    {
        if ($this->getRequired()) {
            throw RuleCheckException::requiredValueIsEmpty();
        }
    }

    protected function checkValueType($value): void
    {
        if ($value === null || $this->type === null) {
            return;
        }

        $type = gettype($value);

        if ($type !== $this->type) {
            throw RuleCheckException::incorrectValueType($type, $this->type);
        }
    }

    protected function checkInstanceOf(object $value): void
    {
        if ($this->instanceOf === null) {
            return;
        }

        if (!($value instanceof $this->instanceOf)) {
            throw RuleCheckException::incorrectValueInstance(get_class($value), $this->instanceOf);
        }
    }
}