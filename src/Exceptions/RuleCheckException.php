<?php

namespace Meumru\MeumTools\Exceptions;

use Meumru\MeumTools\Helpers\Str;

class RuleCheckException extends AbstractToolsException
{
    public static function requiredValueIsEmpty(): self
    {
        return new self('Required value is empty');
    }

    public static function incorrectValueType(string $currentType, string $needType): self
    {
        return new self(Str::format('Value has incorrect type {$currentType} instead of {$needType}', [
            'currentType' => $currentType,
            'needType' => $needType,
        ]));
    }

    public static function incorrectValueInstance(string $currentInstance, string $needInstance): self
    {
        return new self(Str::format('{$currentInstance} is not instance of {$needInstance}', [
            'currentInstance' => $currentInstance,
            'needInstance' => $needInstance,
        ]));
    }
}
