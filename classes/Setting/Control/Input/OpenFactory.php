<?php

declare(strict_types=1);

namespace AC\Setting\Control\Input;

use AC\Setting\AttributeCollection;

final class OpenFactory
{

    public static function create_text(
        string $name,
        ?string $default = null,
        ?string $placeholder = null,
        ?AttributeCollection $attributes = null,
        ?string $append = null
    ): Open {
        return new Open($name, 'text', $default, $placeholder, $attributes, $append);
    }

}