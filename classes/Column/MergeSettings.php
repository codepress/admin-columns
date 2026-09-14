<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Column;
use AC\Setting\ComponentFactory\MergeWithPrevious;

final class MergeSettings
{
    public static function is_enabled(Column $column): bool
    {
        return 'on' === self::read($column, MergeWithPrevious::MERGE);
    }

    public static function get_separator(Column $column): string
    {
        return (string)self::read($column, MergeWithPrevious::SEPARATOR);
    }

    private static function read(Column $column, string $name): ?string
    {
        $setting = $column->get_setting($name);

        if (! $setting || ! $setting->has_input()) {
            return null;
        }

        return (string)$setting->get_input()->get_value();
    }

}
