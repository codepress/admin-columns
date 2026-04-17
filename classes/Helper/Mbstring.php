<?php

declare(strict_types=1);

namespace AC\Helper;

final class Mbstring
{

    public static function strlen(string $string): int
    {
        return function_exists('mb_strlen')
            ? mb_strlen($string)
            : strlen($string);
    }

    public static function strtolower(string $string): string
    {
        return function_exists('mb_strtolower')
            ? mb_strtolower($string)
            : strtolower($string);
    }

    public static function substr(string $string, int $start, ?int $length = null): string
    {
        if (function_exists('mb_substr')) {
            return mb_substr($string, $start, $length);
        }

        return (string)substr($string, $start, $length ?? PHP_INT_MAX);
    }

}
