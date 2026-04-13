<?php

declare(strict_types=1);

namespace AC;

final class Acf
{

    public static function is_active(): bool
    {
        return class_exists('ACF', false) && defined('ACF');
    }

}
