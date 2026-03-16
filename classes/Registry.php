<?php

declare(strict_types=1);

namespace AC;

use Closure;

final class Registry
{

    private static array $items = [];

    public static function get(string $id, $default = null)
    {
        if ( ! self::has($id)) {
            return $default;
        }

        $value = self::$items[$id];

        if ($value instanceof Closure) {
            $value = $value();
        }

        return $value;
    }

    public static function has(string $id): bool
    {
        return array_key_exists($id, self::$items);
    }

    public static function set(string $id, $value): void
    {
        self::$items[$id] = $value;
    }

    public static function clear(): void
    {
        self::$items = [];
    }

}