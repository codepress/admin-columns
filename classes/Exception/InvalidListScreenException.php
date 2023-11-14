<?php

declare(strict_types=1);

namespace AC\Exception;

use AC\Type\ListKey;
use InvalidArgumentException;
use WP_Screen;

final class InvalidListScreenException extends InvalidArgumentException
{

    public static function from_invalid_key(ListKey $key): self
    {
        return new self(sprintf('Invalid key %s', $key));
    }

    // TODO change to a TableScreenException
    public static function from_invalid_screen(WP_Screen $wp_screen): self
    {
        return new self(sprintf('Invalid screen %s', $wp_screen->id));
    }

}