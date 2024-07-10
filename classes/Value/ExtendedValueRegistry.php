<?php

declare(strict_types=1);

namespace AC\Value;

use AC\Value\Extended\ExtendedValue;
use LogicException;

class ExtendedValueRegistry
{

    private static $views = [];

    public static function add(ExtendedValue $view): void
    {
        self::$views[] = $view;
    }

    public function has_view(string $name): bool
    {
        foreach (self::$views as $view) {
            if ($view->can_render($name)) {
                return true;
            }
        }

        return false;
    }

    public function get_view(string $name): ExtendedValue
    {
        foreach (self::$views as $view) {
            if ($view->can_render($name)) {
                return $view;
            }
        }

        throw new LogicException('No extended value found for view: ' . $name);
    }

}