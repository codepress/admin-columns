<?php

declare(strict_types=1);

namespace AC\Setting\Component\Input;

use AC\Setting\Component\AttributeCollection;

final class OpenFactory
{

    public static function create_text(
        string $name,
        string $default = null,
        string $placeholder = null,
        AttributeCollection $attributes = null,
        string $append = null
    ): Open {
        return new Open('text', $default, $placeholder, $attributes, $append);
    }

}