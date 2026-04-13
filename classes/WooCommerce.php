<?php

declare(strict_types=1);

namespace AC;

final class WooCommerce
{

    public static function is_active(): bool
    {
        return class_exists('WooCommerce', false);
    }

}
