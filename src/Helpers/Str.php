<?php

namespace Meumru\MeumTools\Helpers;

class Str
{
    public static function format(string $format, array $arguments = []): string
    {
        $keys = array_map(function($key) {
            return '{$' . $key . '}';
        }, array_keys($arguments));
        $values = array_map(function($value) {
            return (string) $value;
        }, array_values($arguments));

        return str_replace($keys, $values, $format);
    }
}