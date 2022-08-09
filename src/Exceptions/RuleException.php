<?php

namespace Meumru\MeumTools\Exceptions;

use Meumru\MeumTools\Helpers\Str;

class RuleException extends AbstractToolsException
{
    public static function wrongType(string $type): self
    {
        return new static(Str::format('Wrong type: {$type}', ['type' => $type]));
    }

    public static function nonObjectTypeWithInstanceOf(string $type, string $className): self
    {
        return new static(Str::format('Type {$type} can\'t be for instance {$className}', [
            'type' => $type,
            'className' => $className,
        ]));
    }
}
