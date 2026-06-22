<?php

declare(strict_types=1);

namespace AC\Helper;

final class WpDateFormat
{
    private function __construct()
    {
    }

    public static function date(): string
    {
        return (string)get_option('date_format') ?: 'F j, Y';
    }

    public static function time(): string
    {
        return (string)get_option('time_format') ?: 'H:i';
    }

    public static function date_time(): string
    {
        return sprintf(
            '%s %s',
            self::date(),
            self::time()
        );
    }

}
