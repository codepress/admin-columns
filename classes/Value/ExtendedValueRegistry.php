<?php

declare(strict_types=1);

namespace AC\Value;

use AC\Value\Extended\ExtendedValue;

class ExtendedValueRegistry
{

    private static $views = [];

    public static function add(ExtendedValue $view): void
    {
        self::$views[] = $view;
    }

    public function get_view(string $name): ?ExtendedValue
    {
        foreach (self::$views as $view) {
            if ($view->can_render($name)) {
                return $view;
            }
        }

        return null;
    }

}