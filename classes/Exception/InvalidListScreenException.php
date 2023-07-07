<?php

declare(strict_types=1);

namespace AC\Exception;

use InvalidArgumentException;
use WP_Screen;

final class InvalidListScreenException extends InvalidArgumentException
{

    public static function from_invalid_key(string $key): self
    {
        return new self(sprintf('Invalid key %s', $key));
    }

    public static function from_invalid_screen(WP_Screen $wp_screen): self
    {
        return new self(sprintf('Invalid screen %s', $wp_screen->id));
    }

}